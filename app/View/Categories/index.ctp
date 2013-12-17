<h1>Web Crawl</h1>
<?php
echo $this->Form->create('WebCrawl');
echo $this->Form->input('url', array('row'=> '1'));
echo $this->Form->end('Crawl Url');
?>