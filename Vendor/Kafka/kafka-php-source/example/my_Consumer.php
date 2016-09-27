<?php 
require 'vendor/autoload.php';

$consumer = \Kafka\Consumer::getInstance('kafka0:2181');
$group = 'topic_name';
$consumer->setGroup($group);
$consumer->setFromOffset(true);
$consumer->setTopic('topic_name', 0);
$consumer->setMaxBytes(102400);
$result = $consumer->fetch();
print_r($result);
foreach ($result as $topicName => $partition) {
    foreach ($partition as $partId => $messageSet) {
    var_dump($partition->getHighOffset());
        foreach ($messageSet as $message) {
            var_dump((string)$message);
        }
    var_dump($partition->getMessageOffset());
    }
}
?>