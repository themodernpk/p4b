<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com
***************************************************************************/

class CapacityWebSolutions_Inquiry_Block_Adminhtml_Inquiry_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		echo "  <style type='text/css'>
		.cws_created
		{
			color: green;
		} 
		.cws_notcreated  {
			color: red;
		}
		</style>";
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('inquiry_form', array('legend'=>Mage::helper('inquiry')->__('Dealer Information')));

		$data = Mage::registry('inquiry_data');
		$storename = Mage::getModel('core/store')->load($data->getStoreid())->getName();
		$createddt = date("d/m/Y H:i:s", strtotime($data->getCreateddt()));
		$datetimeformat = date("d/m/Y H:i:s", strtotime($data->getDateTime()));

		$date_time = trim(Mage::getStoreConfig('inquiry/change_label/datetime'));
		$extra_field_one = trim(Mage::getStoreConfig('inquiry/change_label/extra_field_one'));
		$extra_field_two = trim(Mage::getStoreConfig('inquiry/change_label/extra_field_two'));
		$extra_field_three = trim(Mage::getStoreConfig('inquiry/change_label/extra_field_three'));


		if(Mage::registry('inquiry_data')->getIscustcreated()){
			$iscustomer = 'Created';
			$cust_class = 'cws_created';
		}else{
			$cust_class = 'cws_notcreated';
			$iscustomer = 'Not Created';
		}

		if($data->getForm()){
			$fieldset->addField('formname', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Form'),
				'value'		=> $data->getForm(),
			));
		}

		if($data->getType()){
			$fieldset->addField('type','label',array(
				'label' => Mage::helper('inquiry')->__('Type'),
				'value' => $data->getType(),
			));
		}

		if($data->getFirstname()){
			$fieldset->addField('name', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Name'),
				'value'		=> $data->getFirstname(),
			));
		}

		if($data->getCountry()){
			$fieldset->addField('country','label',array(
				'label' => Mage::helper('inquiry')->__('Country'),
				'value' => $data->getCountry(),
			));
		}

		if($data->getEmail()){
			$fieldset->addField('email', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Email'),
				'value'		=> $data->getEmail(),
			));
		}

		if($data->getPhone()){
			$fieldset->addField('phone','label',array(
				'label' => Mage::helper('inquiry')->__('Phone'),
				'value' => $data->getPhone(),
			));
		}

		if($data->getRunsize()){
			$fieldset->addField('run size','label',array(
				'label' => Mage::helper('inquiry')->__('Run Size'),
				'value' => $data->getRunsize(),
			));
		}

		if($data->getAdditionalrunsize()){
			$fieldset->addField('additional run size','label',array(
				'label' => Mage::helper('inquiry')->__('Additional Run Size'),
				'value' => $data->getAdditionalrunsize(),
			));
		}

		if($data->getFlatdimension()){
			$fieldset->addField('flat dimension','label',array(
				'label' => Mage::helper('inquiry')->__('Flat Dimension'),
				'value' => $data->getFlatdimension(),
			));
		}

		if($data->getFinisheddimension()){
			$fieldset->addField('finished dimension','label',array(
				'label' => Mage::helper('inquiry')->__('Finished Dimension'),
				'value' => $data->getFinisheddimension(),
			));
		}

		if($data->getPrintslide()){
			$fieldset->addField('printed slides','label',array(
				'label' => Mage::helper('inquiry')->__('Printed Slides'),
				'value' => $data->getPrintslide(),
			));
		}

		if($data->getStocktype()){
			$fieldset->addField('stock type','label',array(
				'label' => Mage::helper('inquiry')->__('Stock Type'),
				'value' => $data->getStocktype(),
			));
		}


		if($data->getBindery()){
			$fieldset->addField('bindery','label',array(
				'label' => Mage::helper('inquiry')->__('Bindery'),
				'value' => $data->getBindery(),
			));
		}

		if($data->getSize()){
			$fieldset->addField('size','label',array(
				'label' => Mage::helper('inquiry')->__('Size'),
				'value' => $data->getSize(),
			));
		}

		if($data->getFinishing()){
			$fieldset->addField('finishing','label',array(
				'label' => Mage::helper('inquiry')->__('Finishing'),
				'value' => $data->getFinishing(),
			));
		}

		if($data->getDesc()){
			$fieldset->addField('description','label',array(
				'label' => Mage::helper('inquiry')->__('Additional Info'),
				'value' => $data->getDesc(),
			));
		}

		if($data->getImagepreview()){
			$media_path = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
			$img_path = $media_path.$data->getImagepreview();
			$fieldset->addField('imagepreview', 'image', array(
				'label'     => Mage::helper('inquiry')->__('Image'),
				'name'      => 'imagepreview',
				'value'		=> $img_path,
			));
		}

		if($data->getFilename()){
			$file_names = explode('|',$data->getFilename());
			foreach($file_names as $key=>$file){
				$file_value = "<a href='".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."inquiry/upload/".$file."' target='_blank'>".$file."</a>";
				$fieldset->addField('filename_'.$key, 'label', array(
					'label'     => Mage::helper('inquiry')->__('Uploaded File'),
					'name'      => 'filename_'.$key,
					'after_element_html'		=> $file_value,
				));
			}
		}

		if ( Mage::getSingleton('adminhtml/session')->getInquiryData()){
			$form->setValues(Mage::getSingleton('adminhtml/session')->getInquiryData());
			Mage::getSingleton('adminhtml/session')->setInquiryData(null);
		}
		return parent::_prepareForm();
	}
}