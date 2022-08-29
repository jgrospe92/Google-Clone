<?php

/*
 * Author: Sleiman Rabah
 * Modified on: Nov 16, 2020
 * Description: a PHP script that parses the content of an XML document. 
 *              When it's invoked, it produces an HTTP response whose MIME type is   
                application/json.
 */

// Report all the runtime errors.
error_reporting(E_ERROR | E_PARSE);

/**
 * Class that translates an XML document to a JSON representation.
 */
class JsonXMLElement extends SimpleXMLElement implements JsonSerializable {

    /**
     * Serializes XML data to JSON data
     *
     * @return mixed data which can be serialized by json_encode.
     */
    public function jsonSerialize() {
        return (object) (array) $this;
    }

}

//-- TODO: write your file name if it's different from what is assigned below.
$xml_file_name = "products.xml";

//-- Creates an empty XML document. 
$dataXMLDocument = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');
//-- Load the XML files that contains the information about a list of items. 
$rootElement = simplexml_load_file($xml_file_name);
// Check if we received a valid search keyword
if (isset($_GET["query"]) && !empty($_GET["query"])) {
    $search_keyword = $_GET["query"];
    //-- Case 1): search keyword provided --> return only the matching elements.
    //--
    //-- Loop through all the categories found in the XML file.
    foreach ($rootElement->category as $categories) {
        $new_category = $dataXMLDocument->addChild("categories");
        //-- Parse the list of categories
        foreach ($categories as $category) {
            if ($category->getName() != "items") {
                $new_category->addChild($category->getName(), $category);
            }
        }
        //-- Parse the matching list of items.
        foreach ($categories->items->item as $items) {
            foreach ($items as $item) {
                //-- Do we have a match?
                if (stripos($item, $search_keyword) !== false) {
                    $new_item = $new_category->addChild("items");
                    foreach ($items as $element) {
                        $new_item->addChild($element->getName(), $element);
                    }
                }
            }
        }
    }
} else {
    //-- Case 2): no search keyword provided --> return all the elements.
    //
    //-- Loop through all the categories found in the XML file.
    foreach ($rootElement->category as $categories) {
        $new_category = $dataXMLDocument->addChild("categories");
        //-- Parse the list of categories
        foreach ($categories as $category) {
            if ($category->getName() != "items") {
                $new_category->addChild($category->getName(), $category);
            }
        }
        //-- Parse all the items.
        foreach ($categories->items->item as $items) {
            $new_items = $new_category->addChild("items");
            foreach ($items as $element) {
                //echo ' erte <br> ' . $element->getName();
                $new_items->addChild($element->getName(), $element);
            }
        }
    }
}
//-- Now we start building the HTTP response. However, we need to set first the mim-type of 
// Set the response's MIME type to application/json.
header('Content-Type: application/json');
// Disable caching.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//-- Embed the JSON document in the HTTP response's body as a well-formatted JSON document .
$xml = simplexml_load_string($dataXMLDocument->asXML(), 'JsonXMLElement');
echo json_encode($xml, JSON_PRETTY_PRINT), "\n";
//echo $dataXMLDocument->asXML();
?>
