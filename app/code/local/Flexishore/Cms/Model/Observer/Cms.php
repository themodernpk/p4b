<?php
/**
 * Extend admin page for editing cms pages (add possibility to upload image)
 *
 * @author Rafał Kos <rafal.k@flexishore.com>
 */
class Flexishore_Cms_Model_Observer_Cms
{

    /**
     * Add image field to form
     *
     * @param Varien_Event_Observer $observer
     *
     * @return void
     */
    public function prepareForm(Varien_Event_Observer $observer)
    {
        $form = $observer->getEvent()->getForm();               
        
        $fieldset = $form->addFieldset(
            'image_fieldset',
            array(
                 'legend' => 'Image',
                 'class' => 'fieldset-wide'
            )
        );

        $fieldset->addField('banner', 'image', array(
            'name' => 'banner',
            'label' => 'banner image',
            'title' => 'banner image'
        ));
    }

    /**
     * Save banner image
     *
     * @param Varien_Event_Observer $observer
     *
     * @return void
     */
    public function savePage(Varien_Event_Observer $observer)
    {
        $model = $observer->getEvent()->getPage();
        $request = $observer->getEvent()->getRequest();

        if (isset($_FILES['banner']['name']) && $_FILES['banner']['name'] != '') {
            $uploader = new Varien_File_Uploader('banner');

            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);

            // Set media as the upload dir
            $media_path  = Mage::getBaseDir('media') . DS . 'banner' . DS;

            // Set thumbnail name
            $file_name = 'cms_';

            // Upload the image
            $uploader->save($media_path, $file_name . $_FILES['banner']['name']);

            $data['banner'] = 'banner' . '/' . $file_name . $_FILES['banner']['name'];

            // Set thumbnail name
            $data['banner'] = $data['banner'];
            $model->setbanner($data['banner']);
        } else {
            $data = $request->getPost();
            if($data['banner']['delete'] == 1) {
                $data['banner'] = '';
                $model->setbanner($data['banner']);
            } else {
                unset($data['banner']);
                $model->setbanner(implode($request->getPost('banner')));
            }
        }
    }

    /**
     * Shortcut to getRequest
     *
     * @return Mage_Core_Controller_Request_Http
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}