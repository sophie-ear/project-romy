<?php

class ItemForm extends Form{

	public function build(){

		$this->addFormField("ProductName");
		$this->addFormField("ProductDescription");
		$this->addFormField("ProductPrice");
		$this->addFormField("quantity");
		$this->addFormField("ProductImage");
	}
}