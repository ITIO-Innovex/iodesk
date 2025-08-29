<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ai_content_generator_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function generate($data)
    {
	   
	   $content_title=trim($data['content_title']);
	   if(isset($content_title)&&!empty($content_title)){
	   $content_title=nl2br(htmlspecialchars($content_title));
		///////////////CHAT GTP API///////////////
		$apikeys=$_SESSION['ai-apikey'];
		if(empty($apikeys)){
		$_SESSION['ai-apikey']="";
		redirect(admin_url('ai_content_generator'));
		}
$secKey = "Bearer $apikeys"; //mailers@itio.in

$post_url = 'https://api.openai.com/v1/chat/completions';
$requestJson='{
     "model": "gpt-4o-mini",
     "messages": [{"role": "user", "content": "'.$content_title.'"}]
   }';

  $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => $post_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$requestJson,
  CURLOPT_HTTPHEADER => array(
    'Authorization: '.$secKey,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$res = json_decode($response,1);

if(isset($res['error'])&&$res['error']){
$data['error']=$res['error']['code']." ".$res['error']['message'];
return $data;
}


if(isset($res['choices'][0]['message']['content'])&&$res['choices'][0]['message']['content']){
$mysqli = $this->db->conn_id; // Get the MySQL connection instance
$data['id']=$res['id'];
$data['object']=$res['object'];
$data['created']=$res['created'];
$data['model']=$res['model'];
$data['role']=$res['choices'][0]['message']['role'];
$data['content']=mysqli_real_escape_string($mysqli, $res['choices'][0]['message']['content']);
$data['finish_reason']=$res['choices'][0]['finish_reason'];
$data['prompt_tokens']=$res['usage']['prompt_tokens'];
$data['completion_tokens']=$res['usage']['completion_tokens'];
$data['total_tokens']=$res['usage']['total_tokens'];
$data['company_id']  = get_staff_company_id();
$this->db->insert(db_prefix().'content_master', $data);
        if ($this->db->affected_rows() > 0) {
		//print_r($data);exit;
		return $data;
		
		}else{
		log_activity('Data Not Submitted');
		return false;
		}
//echo $this->db->last_query();exit;
}
///////////////CHAT GTP API///////////////
log_activity('Data Not Submitted');
return false;
		
		
		
	   }
	   


        
    }
 
   
	public function getlist($id = '', $where = [])
	{
			$this->db->select('content_id,content_title,content,');
			$this->db->where($where);
			$this->db->where('company_id', get_staff_company_id());
			$this->db->order_by('content_id', 'DESC');
			$this->db->limit(5,0);
			return $this->db->get(db_prefix() . 'content_master')->result_array();
			//return 
			//echo $this->db->last_query();exit;
	}
   
    public function getapikey()
	{
			$this->db->select('apikey,');
			$this->db->where('company_id', get_staff_company_id());
			$this->db->limit(1);
			return $this->db->get(db_prefix() . 'ai_details')->result_array();
			//echo $this->db->num_rows();
			//return 
			//echo $this->db->last_query();exit;
	}
	
	//Update Webmail Setup
    public function update($data)
    {
	
	
	    $this->db->select('id');
        $this->db->where('company_id', get_staff_company_id());
        $nor = $this->db->get(db_prefix() . 'ai_details')->row();
        if($nor){
          $this->db->where('company_id', get_staff_company_id());
          $this->db->update(db_prefix().'ai_details', $data);   
        }else{
		$data['company_id'] = get_staff_company_id();
        $this->db->insert(db_prefix() . 'ai_details', $data);
        
        }
		
	
        
		//echo $this->db->last_query();exit;

        if ($this->db->affected_rows() > 0) {
		$_SESSION['ai-apikey']="";
            return true;
        }

        return false;
    }

    // AI Provider Management Methods
    public function get_ai_providers($list='')
    {
	    if(isset($list)&&$list==1){
		$this->db->select('provider_name,provider_url,viewon,');
		}else{
        $this->db->select('*');
		}
        $this->db->from(db_prefix() . 'ai_providers');
        $this->db->order_by('id', 'DESC');
		$this->db->where('company_id', get_staff_company_id());
        return $this->db->get()->result_array();
    }

    public function get_ai_provider($id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'ai_providers');
        $this->db->where('id', $id);
		$this->db->where('company_id', get_staff_company_id());
        return $this->db->get()->row_array();
    }

    public function add_ai_provider($data)
    {
        $this->db->insert(db_prefix() . 'ai_providers', $data);
        return $this->db->affected_rows() > 0;
    }

    public function update_ai_provider($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'ai_providers', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_ai_provider($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'ai_providers');
        return $this->db->affected_rows() > 0;
    }
    
    
}
