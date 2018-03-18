<?php

use App\Form\UpdatePostForm;
use App\Lib\Api;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;

// Load detail
$data = null;
if (!empty($id)) {
    // Edit
    $param['id'] = $id;
    $data = Api::call(Configure::read('API.url_posts_detail'), $param);
    $this->Common->handleException(Api::getError());
    if (empty($data)) {
        return $this->Flash->error(__('MESSAGE_DATA_NOT_EXIST'));
    }
    
    $pageTitle = __('LABEL_POST_UPDATE');
} else {
    // Create new
    $pageTitle = __('LABEL_ADD_NEW');
}

$cates = $this->Common->arrayKeyValue(Api::call(Configure::read('API.url_cates_all'), array()), 'id', 'name');
$types = array(
    0 => 'Tin tức',
    1 => 'Hình ảnh',
    2 => 'Video'
);
// Create breadcrumb
$listPageUrl = h($this->BASE_URL . '/posts');
$this->Breadcrumb->setTitle($pageTitle)
    ->add(array(
        'link' => $listPageUrl,
        'name' => __('LABEL_POST_LIST'),
    ))
    ->add(array(
        'name' => $pageTitle,
    ));

// Create Update form 
$form = new UpdatePostForm();
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
        'id' => 'cate_id',
        'label' => __('LABEL_CATE'),
        'options' => $cates,
        'empty' => '-'
    ))
    ->addElement(array(
        'id' => 'image',
        'label' => __('LABEL_IMAGE'),
        'image' => true,
        'type' => 'file'
    ))
    ->addElement(array(
        'id' => 'description',
        'label' => __('LABEL_DESCRIPTION'),
        'empty' => ''
    ))
    ->addElement(array(
        'id' => 'keyword',
        'label' => __('LABEL_KEYWORD'),
        'empty' => ''
    ))
    ->addElement(array(
        'id' => 'content',
        'label' => __('LABEL_CONTENT'),
        'type' => 'editor'
    ))
    ->addElement(array(
        'id' => 'type',
        'label' => __('LABEL_TYPE'),
        'options' => $types,
        'empty' => '0'
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
        if (!empty($data['image']['name'])) {
            $filetype = $data['image']['type'];
            $filename = $data['image']['name'];
            $filedata = $data['image']['tmp_name'];
            $data['image'] = new CurlFile($filedata, $filetype, $filename);
        }
        // Call API
        $id = Api::call(Configure::read('API.url_posts_addupdate'), $data);
        if (!empty($id) && !Api::getError()) {            
            $this->Flash->success(__('MESSAGE_SAVE_OK'));
            return $this->redirect("{$this->BASE_URL}/{$this->controller}/update/{$id}");
        } else {
            return $this->Flash->error(__('MESSAGE_SAVE_NG'));
        }
    }
}