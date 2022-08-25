<?php
// open the script at the first byte
// this is a comment
echo 'hello world!';
// create a variable
// PHP = PHP Hypertext Processor
$kidAge = 6;
$message = "Bobby is $kidAge years old";

echo '<br>', $message, '<br>';
echo 'Finally, my xampp server is working';

// declaring class
class Example {
    public function __construct(){
        echo "constructor";

    }
    public static function message(){
        echo "This is a message";
    }
  }

new Example();
// call a static method
Example::message();

// assigned a  method to a variable
$name = 'message';
Example::$name();

?>