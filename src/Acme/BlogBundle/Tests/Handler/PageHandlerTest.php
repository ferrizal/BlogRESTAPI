<?php
public function testGet()
	{
		$id = 1;
		$page = $this->getPage(); // create a Page object
		// I expect that the Page repository is called with find(1)
		$this->repository->expects($this->once())
			->method('find')
			->with($this->equalTo($id))
			->will($this->returnValue($page));
	
		$this->pageHandler->get($id); // call the get.
	}