<?php
App::uses('CloudFiles','CloudFiles.Lib');
class CloudFilesTest extends CakeTestCase {
	
	function startTest(){
		CloudFiles::$errors = array();
	}

	function test_upload(){
		$retval = CloudFiles::upload(WWW_ROOT . 'img/hearing_aids_thumb.png','images');
		$this->assertTrue(!empty($retval));
	}
	
	function test_delete(){
		$retval = CloudFiles::delete('hearing_aids_thumb.png','images');
		$this->assertTrue($retval);
	}
	
	function test_ls(){
		$retval = CloudFiles::ls('images');
		$this->assertEqual(1, count($retval));
	}
	
	function test_upload_noexist(){
		$this->setExpectedException('CloudFilesException');
		$retval = CloudFiles::upload(WWW_ROOT . 'img/no_exist.png','images');
		$this->assertFalse($retval);
		$this->assertEqual('File does not exist.', CloudFiles::$errors[0]);
	}
	
	function test_upload_nocontainer(){
		$this->setExpectedException('CloudFilesException');
		$retval = CloudFiles::upload(WWW_ROOT . 'img/no_exist.png');
		$this->assertFalse($retval);
		$this->assertEqual('File path and container required.', CloudFiles::$errors[0]);
	}
	
	function test_upload_nofile(){
		$this->setExpectedException('CloudFilesException');
		$retval = CloudFiles::upload();
		$this->assertFalse($retval);
		$this->assertEqual('File path and container required.', CloudFiles::$errors[0]);
	}
	
	function test_url(){
		$retval = CloudFiles::url('jessica_k.png', 'images');
		$this->assertTrue(!empty($retval));
		
		$this->setExpectedException('NoSuchObjectException');
		$retval = CloudFiles::url('no_exist.png', 'images');
		$this->assertFalse($retval);
	}
	
	function test_stream(){
		$retval = CloudFiles::stream('jessica_k.png', 'images');
		$this->assertTrue(!empty($retval));
		
		$this->setExpectedException('NoSuchObjectException');
		$retval = CloudFiles::stream('no_exist.png', 'images');
		$this->assertFalse($retval);
	}
	
	function test_download(){
		$path = APP . 'Plugin' . DS . 'CloudFiles' . DS . 'webroot' . DS . 'jessica_k.png';
		@unlink($path);
		$this->assertFalse(file_exists($path));
		$retval = CloudFiles::download('jessica_k.png', 'images', $path);
		$this->assertTrue($retval);
		$this->assertTrue(file_exists($path));
		@unlink($path);
	}
	
	function test_listContainer(){
		$retval = CloudFiles::listContainers();
		$this->assertTrue(!empty($retval));
	}
	
	function test_createContainer(){
		$retval = CloudFiles::createContainer('delme');
		$this->assertTrue(is_object($retval));
		$containers = CloudFiles::listContainers();
		$this->assertTrue(in_array('delme', $containers));
	}
	
	function test_deleteContainer(){
		$containers = CloudFiles::listContainers();
		$this->assertTrue(in_array('delme', $containers));
		$retval = CloudFiles::deleteContainer('delme');
		$this->assertTrue($retval);
		$containers = CloudFiles::listContainers();
		$this->assertFalse(in_array('delme', $containers));
	}
}
?>
