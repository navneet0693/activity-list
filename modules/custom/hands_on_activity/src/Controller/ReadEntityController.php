<?php

/**
 * @file
 * Contains \Drupal\products\Controller\ProductsController.
 */
namespace Drupal\hands_on_activity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use \Drupal\taxonomy\Entity\Vocabulary;

/**
 * Controller routines for products routes.
 */
class ReadEntityController extends ControllerBase {

  public function getData($type) {
    if ($type == 'read') {
    $config = \Drupal::service('config.factory')->getEditable('hands_on_activity.custom_xml_import');
    $xmlImportUrl = $config->get('xml_import_url');
    $sourceVoca = Vocabulary::load('source');
    if (empty($sourceVoca)) {
      // Create a vocabulary.
      $source = Vocabulary::create([
        'name' => 'Source',
        'vid' => 'source',
      ]);
      $source->save();
    }
    $categoryVoca = Vocabulary::load('category');
    if (empty($categoryVoca)) {
      // Create a vocabulary.
      $source = Vocabulary::create([
        'name' => 'Category',
        'vid' => 'category',
      ]);
      $source->save();
    }
    if ('' != $xmlImportUrl) {
      $xml = simplexml_load_file($xmlImportUrl,'SimpleXMLElement', LIBXML_NOCDATA);
      $data = $xml->channel->item;
      $nids = \Drupal::entityQuery('node')->condition('type','article')->execute();
      if ($nids) {
        ReadEntityController::read_entity_update_request($data, $nids);
      }else{
        ReadEntityController::read_entity_insert_request($data);
      }
    }else{
      echo "Missing Configuration.";
    }
    }
  }
/**
 * Custom callback function.
 */
  public function read_entity_update_request($data = array(),$nids = array()) {
    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
    foreach ($data as $dataKey => $dataValue) {
      $guids[] = $dataValue->guid;
    }
    foreach ($nodes as $node){
       $guid = $node->get('field_guid')->value;
       if (!in_array($guid, $guids)) {
         $deletenodes[] = $node->get('nid')->value;
       }else{
        $matchnodes[] = $guid;
       }
    }

    $storage_handler = \Drupal::entityTypeManager()->getStorage("node");
    $entities = $storage_handler->loadMultiple($deletenodes);
    $storage_handler->delete($entities);

    foreach ($data as $dataImp => $dataVal) {
      if (!in_array($dataVal->guid, $matchnodes)) {
        $guid = $dataVal->guid;
        $title = $dataVal->title;
        $link = $dataVal->link;
        $updatedAt = $dataVal->updatedAt;
        $pubDate = $dataVal->pubDate;
        $StoryImage = $dataVal->StoryImage;
        $category = $dataVal->category;
        $fullimage = $dataVal->fullimage;
        $source = $dataVal->source;
        $categoryPro = $sourcePro = [];
        $categoryTermVal = $sourceTermVal = '';
        $categoryPro['name'] = $category;
        $categoryPro['vid'] = 'category';
        $categoryTerm = Term::create([
          'vid' => 'category',
          'name' => $category,
          'parent' => array(0),
        ]);
        $categoryTerm->save();
        $categoryTermVal = $categoryTerm->id();
        $sourcePro['name'] = $source;
        $sourcePro['vid'] = 'source';
        $sourceTerm = Term::create([
          'vid' => 'source',
          'name' => $source,
          'parent' => array(0),
        ]);
        $sourceTerm->save();
        $sourceTermVal = $sourceTerm->id();
        if (!empty($fullimage)) {
          $data = file_get_contents($fullimage);
          $fileInfoArr = explode('/', $fullimage);
          $file_info['file_name'] = end($fileInfoArr);
          if (!file_exists('sites/default/files/hands_on_activity/images')) {
               mkdir('sites/default/files/hands_on_activity/images', 0777, true);
           }
          $file_dir = "public://hands_on_activity/images/". $file_info['file_name'];
          $file = file_save_data($data, $file_dir, FILE_EXISTS_REPLACE);
          $fid = $file->id();
        }
        $node = Node::create([
              'type'        => 'article',
              'title'       => $title,
              'uid' => 1,
              'created' => date('U', strtotime($pubDate)),
              'changed' => date('U', strtotime($updatedAt)),
              'field_category' => $categoryTermVal,
              'field_source' => $sourceTermVal,
              'field_guid' => $guid,
              'field_link' => [
                    'uri' => $link,
                    'title' => $link,
                    'options' => ["target" => "_blank"],
                  ],
            ]);
        if (!empty($fid)) {
          $node->field_storyimage[] = [
            'target_id' => $fid,
            'alt' => 'Alt text',
            'title' => 'Title',
          ];
        }
        $node->save();
      }
    }
  }
/**
 * Custom callback function.
 */
  public function read_entity_insert_request($data = array()) {
    foreach ($data as $dataKey => $dataValue) {
      $guid = $dataValue->guid;
      $title = $dataValue->title;
      $link = $dataValue->link;
      $updatedAt = $dataValue->updatedAt;
      $pubDate = $dataValue->pubDate;
      $StoryImage = $dataValue->StoryImage;
      $category = $dataValue->category;
      $fullimage = $dataValue->fullimage;
      $source = $dataValue->source;
      $categoryPro = $sourcePro = [];
      $categoryTermVal = $sourceTermVal = '';
      $categoryPro['name'] = $category;
      $categoryPro['vid'] = 'category';
      $categoryTerm = Term::create([
        'vid' => 'category',
        'name' => $category,
        'parent' => array(0),
      ]);
      $categoryTerm->save();
      $categoryTermVal = $categoryTerm->id();
      $sourcePro['name'] = $source;
      $sourcePro['vid'] = 'source';
      $sourceTerm = Term::create([
        'vid' => 'source',
        'name' => $source,
        'parent' => array(0),
      ]);
      $sourceTerm->save();
      $sourceTermVal = $sourceTerm->id();
      if (!empty($fullimage)) {
        $data = file_get_contents($fullimage);
        $fileInfoArr = explode('/', $fullimage);
        $file_info['file_name'] = end($fileInfoArr);
        if (!file_exists('sites/default/files/hands_on_activity/images')) {
             mkdir('sites/default/files/hands_on_activity/images', 0777, true);
         }
        $file_dir = "public://hands_on_activity/images/". $file_info['file_name'];
        $file = file_save_data($data, $file_dir, FILE_EXISTS_REPLACE);
        $fid = $file->id();
      }
      $node = Node::create([
            'type'        => 'article',
            'title'       => $title,
            'uid' => 1,
            'created' => date('U', strtotime($pubDate)),
            'changed' => date('U', strtotime($updatedAt)),
            'field_category' => $categoryTermVal,
            'field_source' => $sourceTermVal,
            'field_guid' => $guid,
            'field_link' => [
                  'uri' => $link,
                  'title' => $link,
                  'options' => ["target" => "_blank"],
                ],
          ]);
      if (!empty($fid)) {
        $node->field_storyimage[] = [
          'target_id' => $fid,
          'alt' => 'Alt text',
          'title' => 'Title',
        ];
      }
      $node->save();
    }
  }
}
?>