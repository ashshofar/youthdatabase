<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Category extends CI_Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_category','category');
        
    }

    private function _init()
    {
        $this->output->set_template('admin');
    }
 
    public function index()
    {
        $this->_init();
        $this->load->helper('url');
        $this->load->view('category/index');
    }
 
    public function ajax_list()
    {
        $list = $this->category->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $category) {
            $no++;
            $row = array();
            $row[] = $category->issue;
            $row[] = $category->description;
            
            $row[] = '<a class="btn btn-sm btn-success btn-xs" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$category->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger btn-xs" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$category->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->category->count_all(),
                        "recordsFiltered" => $this->category->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->category->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $data = array(
                'issue' => $this->input->post('issue'),
                'description' => $this->input->post('description'),
            );
        $insert = $this->category->save($data);
        
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $data = array(
                'issue' => $this->input->post('issue'),
                'description' => $this->input->post('description'),
                );
        $this->category->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_delete($id)
    {
        $this->category->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
 
}