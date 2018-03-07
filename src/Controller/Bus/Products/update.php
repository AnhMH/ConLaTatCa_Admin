<?php

use App\Form\UpdateProductForm;
use App\Lib\Api;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;

// Load detail
$data = null;
if (!empty($id)) {
    // Edit
    $param['id'] = $id;
    $data = Api::call(Configure::read('API.url_products_detail'), $param);
    $this->Common->handleException(Api::getError());
    if (empty($data)) {
        AppLog::info("Product unavailable", __METHOD__, $param);
        throw new NotFoundException("Product unavailable", __METHOD__, $param);
    }
    
    $pageTitle = __('LABEL_PRODUCT_UPDATE');
} else {
    // Create new
    $pageTitle = __('LABEL_ADD_NEW');
}

$suppliers = $this->Common->arrayKeyValue(Api::call(Configure::read('API.url_suppliers_all'), array()), 'id', 'name');

// Create breadcrumb
$listPageUrl = h($this->BASE_URL . '/products');
$this->Breadcrumb->setTitle($pageTitle)
    ->add(array(
        'link' => $listPageUrl,
        'name' => __('LABEL_PRODUCT_LIST'),
    ))
    ->add(array(
        'name' => $pageTitle,
    ));

// Create Update form 
$form = new UpdateProductForm();
$this->UpdateForm->reset()
    ->setModel($form)
    ->setData($data)
    ->setAttribute('autocomplete', 'off')
    ->addElement(array(
        'id' => 'id',
        'type' => 'hidden',
        'label' => __('id'),
    ))
    ->addElement(array(
        'id' => 'name',
        'label' => __('LABEL_NAME'),
        'required' => true,
    ))
    ->addElement(array(
        'id' => 'supplier_id',
        'label' => __('LABEL_SUPPLIER'),
        'options' => $suppliers,
        'empty' => '-'
    ))
    ->addElement(array(
        'id' => 'avatar',
        'label' => __('LABEL_AVATAR'),
        'image' => true,
        'type' => 'file'
    ))
    ->addElement(array(
        'id' => 'price',
        'label' => __('LABEL_PRICE'),
    ))
    ->addElement(array(
        'id' => 'qty',
        'label' => __('LABEL_STOCK'),
    ))
    ->addElement(array(
        'id' => 'description',
        'label' => __('LABEL_DESCRIPTION'),
        'type' => 'editor'
    ))
    ->addElement(array(
        'type' => 'submit',
        'value' => __('LABEL_SAVE'),
        'class' => 'btn btn-primary',
    ))
    ->addElement(array(
        'type' => 'submit',
        'value' => __('LABEL_CANCEL'),
        'class' => 'btn',
        'onclick' => "return back();"
    ));

// Valdate and update
if ($this->request->is('post')) {
    // Trim data
    $data = $this->request->data();
    foreach ($data as $key => $value) {
        if (is_scalar($value)) {
            $data[$key] = trim($value);
        }
    }
    // Validation
    if ($form->validate($data)) {
        if (!empty($data['avatar']['name'])) {
            $filetype = $data['avatar']['type'];
            $filename = $data['avatar']['name'];
            $filedata = $data['avatar']['tmp_name'];
            $data['avatar'] = new CurlFile($filedata, $filetype, $filename);
        }
        // Call API
        $id = Api::call(Configure::read('API.url_products_addupdate'), $data);
        if (!empty($id) && !Api::getError()) {            
            $this->Flash->success(__('MESSAGE_SAVE_OK'));
            return $this->redirect("{$this->BASE_URL}/{$this->controller}/update/{$id}");
        } else {
            return $this->Flash->error(__('MESSAGE_SAVE_NG'));
        }
    }
}