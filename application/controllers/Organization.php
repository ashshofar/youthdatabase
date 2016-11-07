<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Organization extends CI_Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_organization','organization');
        
    }

    private function _init()
    {
        $this->output->set_template('admin');
    }
 
    public function index()
    {
        $this->_init();
        $this->load->helper('url');
        $data['categories'] = $this->organization->getAllCategories();
        $this->load->view('organization/index', $data);

    }
 
    public function ajax_list()
    {
        $list = $this->organization->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $organization) {
            $no++;
            $row = array();
            $row[] = $organization->name;
            $row[] = $organization->category;
            $row[] = $organization->address;
            $row[] = $organization->email;
            $row[] = $organization->telephone;
            
            $row[] = '<a class="btn btn-sm btn-success btn-xs" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$organization->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger btn-xs" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$organization->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->organization->count_all(),
                        "recordsFiltered" => $this->organization->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->organization->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $data = array(
                'name' => $this->input->post('name'),
                'category' => $this->input->post('category'),
                'detail' => $this->input->post('detail'),
                'address' => $this->input->post('address'),
                'telephone' => $this->input->post('telephone'),
                'email' => $this->input->post('email'),
                'logo' => $this->input->post('logo'),
            );
        $insert = $this->organization->save($data);
        
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $data = array(
                'name' => $this->input->post('name'),
                'category' => $this->input->post('category'),
                'detail' => $this->input->post('detail'),
                'address' => $this->input->post('address'),
                'telephone' => $this->input->post('telephone'),
                'email' => $this->input->post('email'),
                'logo' => $this->input->post('logo'),
                );
        $this->organization->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_delete($id)
    {
        $this->organization->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
 
}