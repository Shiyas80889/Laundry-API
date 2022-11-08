<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_model extends CI_Model {


public function login($mobileNumber){
	$otp = $this->common->otpGenerator(4);
	$this->db
    		->select('user_id as userId,is_active as isActive')
    		->from('user')
			->where('mobile_number', $mobileNumber);
		$data = $this->db->get()->row();
			
		if(!$data){			
			
			$isnert_a = ['mobile_number' => $mobileNumber, 'otp' => $otp];
    		$this->db->insert('user', $isnert_a);
			$inserted_id = $this->db->insert_id();
			
			if($inserted_id){
				$this->sendOtp($mobileNumber,$otp);
				return ['status' => true, 'message' => 'otp generated successfully'];
			}	
			return ['status' => false, 'message' => 'something went wrong'];
		}	
		$userId = $data->userId;
		$isActive = $data->isActive;
        $update_a = ['otp' => $otp];
		$this->db
				->where('user_id',$userId )
				->update('user', $update_a);
		$update_res = $this->db->affected_rows();
    	if($update_res){
			$resp = $this->sendOtp($mobileNumber,$otp);
			return ['status' => true, 'message' => 'Otp Generated'];
			
    	}else{
    		return ['status' => false, 'message' => 'Sorry some error occured!'];
    	}			
  
    

}

public function loginVendor($userNameVendor,$passwordVendor){
	
	$this->db
    		->select('vendor_id as vendorId,is_active as isActive')
    		->from('vendor')
			->where('user_name_vendor', $userNameVendor)
			->where('password_vendor', $passwordVendor);

		$data = $this->db->get()->row();
			
		if(!$data){			
			
			return ['status' => false, 'message' => 'Sorry some error occured!'];
		}	
             return ['status' => true, 'data' => $data];
    }


public function validateOtp($mobileNumber,$otp){
	$this->db
    		->select('user_id as userId,is_active as isActive')
    		->from('user')
    		->where('otp', $otp)
    		->where('mobile_number', $mobileNumber);

		$data = $this->db->get()->row();
		
		if(!$data){
			return (object)['status' => false, 'message' => 'Please enter a valid otp'];
		}        
		$userId = $data->userId;
		$isActive = $data->isActive;
		$update_a = ['otp' => ''];
		$this->db
    		->where('user_id',$userId )
			->update('user', $update_a);
		$update_res = $this->db->affected_rows();
		
return (object)['status' => true, 'message' => 'Otp is valid', 'userId' => $userId , 'isActive' => $isActive];
}


public function signup($userName,$userId,$address,$landmark){
	date_default_timezone_set('Asia/Kolkata');
			$dateNow = date("Y-m-d H:i:s");
    $this->db
        ->select('user_id as userId,mobile_number as mobileNumber,is_active as isActive')
        ->from('user')
		->where('user_id', $userId);
		
				
		$data = $this->db->get()->row();
		if(!$data){
			return (object)['status' => false, 'message' => "no user found"];
		 }	
		$update_a = ['user_name' => $userName,'address' => $address,'landmark' => $landmark,'is_active' => '1','user_created_at'=> $dateNow];
		$this->db->where('user_id', $userId);
		$this->db->update('user', $update_a);
		$update_res = $this->db->affected_rows();
       		
		return (object)['status' => true, 'message' => "user updated successfully",
		'userId' => $data->userId,'is_active'=> $data->isActive];
	
	
	}		

	public function listShop(){
		$this->db
			->select('shop_id as shopId,shop_name as shopName,shop_address as shopAddress,shop_lattitude as shopLattitude,shop_longitude as shopLongitude,shop_status as shopStatus')
			->from('shop')
			->where('is_active', 1)
			->where('is_deleted',0);
		$shop = $this->db->get()->result();
		if(!$shop){
			return (object)['status' => false, 'message' => "no shop exist"];
		}
		
		return (object)['status' => true, 'shop' =>$shop ];
}	

public function listService(){
		$this->db
				->select('service_id as serviceId,service_name as serviceName,is_active as isActive')
				->from('service')
				
				->where('is_active', 1)
				->where('is_deleted',0);
		$services = $this->db->get()->result();
		if(!$services){
			return (object)['status' => false, 'message' => "No services found"];
		 }
		 return (object)['status' =>true, 'services' => $services];
	}
	public function createAddress($userId,$userAddress,$lattitude,$longitude,$landmark,$saveAs,$googleAddress){
		
		$this->db
				->select('user_id as userId,user_address_id as userAddressId, lattitude,longitude,landmark,save_as as saveAs,google_address as googleAddress')					
				->from('user_address')
				->where('user_id',$userId)
				->where('save_as',$saveAs)
				->where('is_active', 1)
				->where('is_deleted',0);
			$data = $this->db->get()->result();
			if($data){
						return (object)['status' => false, 'message' => "Address tag already exist"];
					}
				
		$insert_a = ['user_id' => $userId,'user_address' => $userAddress,'lattitude' => $lattitude,'longitude' => $longitude,'save_as' => $saveAs,'google_address' => $googleAddress,'landmark' => $landmark ];	
				 $this->db->insert('user_address', $insert_a);
				 $inserted_id = $this->db->insert_id();
				 if($inserted_id){	
					return (object)['status' => true, 'message' => 'Address updated successfully'];
		
				}
					return (object)['status' => false, 'message' => "something went wrong"];
					 
				}
public function listAddresses($userId){
	$this->db
	->select('user_id as userId,user_address_id as userAddressId,user_address as userAddress, lattitude,longitude,landmark,save_as as saveAs,google_address as googleAddress')					
	->from('user_address')
	->where('user_id',$userId)
	
	->where('is_active', 1)
	->where('is_deleted',0);
	$address = $this->db->get()->result();
	if(!$address){
	return (object)['status' => false, 'message' => "No address found"];
	}
     return (object)['status' =>true, 'address' => $address];
	}	
	public function listAddressesTag($userAddressId){
		$this->db
		->select('user_id as userId,user_address_id as userAddressId,user_address as userAddress, lattitude,longitude,landmark,save_as as saveAs,google_address as googleAddress')					
		->from('user_address')
		->where('user_address_id',$userAddressId)
		
		->where('is_active', 1)
		->where('is_deleted',0);
		$addressTag = $this->db->get()->result();
		if(!$addressTag){
		return (object)['status' => false, 'message' => "No address found"];
		}
		 return (object)['status' =>true, 'address_tag' => $addressTag];
		}					
		
/*public function createShopItems($shopId,$serviceId){
$this->db	
		->select('shop_service_id as shopServiceId,shop_id as shopId,service_id as serviceId')					
		->from('shop_service')
		->where('shop_id',$shopId)
		->where('service_id',$serviceId)
		->where('is_active', 1)
		->where('is_deleted',0);
	$data = $this->db->get()->result();
	if($data){
				return (object)['status' => false, 'message' => "Already Exist."];
			}
		
$insert_a = ['shop_id' => $shopId,'service_id' => $serviceId ];	
		 $this->db->insert('shop_service', $insert_a);
		 $inserted_id = $this->db->insert_id();
		 if($inserted_id){	
			return (object)['status' => true, 'message' => 'updated successfully'];

		}
			return (object)['status' => false, 'message' => "something went wrong"];
			 
		} 
public function listShopServices($shopId){
		$this->db
			->select('shop_service_id as shopServiceId,shop_id as shopId,service_id as serviceId,is_active as isActive')
			->from('shop_service')
			->where('shop_id', $shopId)
			->where('is_active', 1)
			->where('is_deleted',0);
			$shopServices = $this->db->get()->result();
			if(!$shopServices){
				return (object)['status' => false, 'message' => "something went wrong"];
			 }
			 return (object)['status' =>true, 'shopServices' => $shopServices];
	
			}

public function createPincodeDetails($shopId,$pincode,$pincodeStatus){
			$this->db 
			->select('pincode_id as pincodeId,pincode as pincode,shop_id as shopId,pincode_status as pincodeStatus')
			->from('pincode_details')
			->where('shop_id',$shopId)
			->where('pincode',$pincode)
			->where('is_active', 1)
			->where('is_deleted',0);
			$data = $this->db->get()->result();
			if($data){
				return (object)['status' => false, 'message' => "Already exist"];
			 }

		
			 $insert_a = ['shop_id' => $shopId,'pincode' => $pincode,'pincode_status' => $pincodeStatus ];	
			 $this->db->insert('pincode_details', $insert_a);
			 $inserted_id = $this->db->insert_id();
			 if($inserted_id){	
				return (object)['status' => true, 'message' => 'updated successfully'];
	
			}
				return (object)['status' => false, 'message' => "something went wrong"];
				 
			}

public function listPincodeDetails($shopId){
			
	$this->db 
	->select('pincode_id as pincodeId,pincode as pincode,shop_id as shopId,pincode_status as pincodeStatus')
	->from('pincode_details')
	->where('shop_id',$shopId)
	->where('is_active', 1)
	->where('is_deleted',0);
	$pincodeDetails = $this->db->get()->result();
	if(!$pincodeDetails){
		return (object)['status' => false, 'message
		' => "Something went wrong "];
	 }
		 
		return (object)['status' => true, 'pincodeDetails' =>$pincodeDetails];
	
	}*/
	
	public function listPriceList($serviceId){
		//service_id, service_name, is_active, is_deleted
		$this->db 
				->select('category_id as categoryId,category_name as categoryName')
				->from('category')
				//->where('user_id', $userId)
				->where('is_active', 1)
				->where('is_deleted',0);
		$data = $this->db->get()->result();		
		if($serviceId == 1){//laundry
			$unsetKeys = array();
			for($i=0;$i<sizeof($data);$i++){
			$this->db 
				->select('item_name as item,laundry_rate as rate')
				->from('price_list')
			    ->where('category_id', $data[$i]->categoryId)	
				->where('laundry_rate >', 0)			
				->where('is_active', 1)
				->where('is_deleted',0);
			$dataX = $this->db->get()->result();
			if($dataX){
				$data[$i]->name = $data[$i]->categoryName;
				$data[$i]->item = 	$dataX;	
			}else{
				array_push($unsetKeys,$i);
			}
			
			}  
			for($j=0;$j < sizeof($unsetKeys); $j++){
				$id = $unsetKeys[$j];
				unset($data[$id]); //removing objects by index
			}		
			$data = array_values($data);
			
		}
		if($serviceId == 2){//dry cleaning
			$unsetKeys = array();
			for($i=0;$i<sizeof($data);$i++){
			$this->db 
				->select('item_name as item,dry_cleaning_rate as rate')
				->from('price_list')
				->where('category_id', $data[$i]->categoryId)
				->where('dry_cleaning_rate >', 0)					
				->where('is_active', 1)
				->where('is_deleted',0);
			$dataX = $this->db->get()->result();
			if($dataX){
				$data[$i]->name = $data[$i]->categoryName;
				$data[$i]->item = 	$dataX;	
			}else{
				array_push($unsetKeys,$i);
			}
			
			}  
			for($j=0;$j < sizeof($unsetKeys); $j++){
				$id = $unsetKeys[$j];
				unset($data[$id]); //removing objects by index
			}		
			$data = array_values($data);
			}
			
		
		if($serviceId == 3){//ironing
			$unsetKeys = array();
			for($i=0;$i<sizeof($data);$i++){
			$this->db 
				->select('item_name as item,ironing_rate as rate')
				->from('price_list')
				->where('category_id', $data[$i]->categoryId)
				->where('ironing_rate >', 0)					
				->where('is_active', 1)
				->where('is_deleted',0);
			$dataX = $this->db->get()->result();
			if($dataX){
				$data[$i]->name = $data[$i]->categoryName;
				$data[$i]->item = 	$dataX;	
			}else{
				array_push($unsetKeys,$i);
			}
			
			}  
			for($j=0;$j < sizeof($unsetKeys); $j++){
				$id = $unsetKeys[$j];
				unset($data[$id]); //removing objects by index
			}		
			$data = array_values($data);
			
		}
		if($serviceId == 4){//shoeCleaning
			$unsetKeys = array();
			for($i=0;$i<sizeof($data);$i++){
			$this->db 
				->select('item_name as item,shoe_cleaning as rate')
				->from('price_list')
				->where('category_id', $data[$i]->categoryId)
				->where('shoe_cleaning >', 0)					
				->where('is_active', 1)
				->where('is_deleted',0);
			$dataX = $this->db->get()->result();
			if($dataX){
				$data[$i]->name = $data[$i]->categoryName;
				$data[$i]->item = 	$dataX;	
			}else{
				array_push($unsetKeys,$i);

			}
			
			}  
			for($j=0;$j < sizeof($unsetKeys); $j++){
				$id = $unsetKeys[$j];
				unset($data[$id]); //removing objects by index
			}		
			$data = array_values($data);
			
			}
			
		
		if($serviceId == 5){//bagCleaning
			$unsetKeys = array();
			for($i=0;$i<sizeof($data);$i++){
			$this->db 
				->select('item_name as item,bag_cleaning as rate')
				->from('price_list')
				->where('category_id', $data[$i]->categoryId)
				->where('bag_cleaning >', 0)					
				->where('is_active', 1)
				->where('is_deleted',0);
			$dataX = $this->db->get()->result();
			if($dataX){
				$data[$i]->name = $data[$i]->categoryName;
				$data[$i]->item = 	$dataX;	
			}else{
				array_push($unsetKeys,$i);
			}
			
			}  
			for($j=0;$j < sizeof($unsetKeys); $j++){
				$id = $unsetKeys[$j];
				unset($data[$id]); //removing objects by index
			}		
			$data = array_values($data);
		}
		if($serviceId == 6){//commercial
			$unsetKeys = array();
			for($i=0;$i<sizeof($data);$i++){
			$this->db 
				->select('item_name as item,commercial as rate')
				->from('price_list')
				->where('category_id', $data[$i]->categoryId)	
				->where('commercial >', 0)				
				->where('is_active', 1)
				->where('is_deleted',0);
			$dataX = $this->db->get()->result();
			if($dataX){
				$data[$i]->name = $data[$i]->categoryName;
				$data[$i]->item = 	$dataX;	
			}else{
				array_push($unsetKeys,$i);
			}
			
			}  
			for($j=0;$j < sizeof($unsetKeys); $j++){
				$id = $unsetKeys[$j];
				unset($data[$id]); //removing objects by index
			}		
			$data = array_values($data);
			
		}	
		if($serviceId == 7){//ironing_with_starching_rate
			$unsetKeys = array();
			for($i=0;$i<sizeof($data);$i++){
			$this->db 
				->select('item_name as item,ironing_with_starching_rate as rate')
				->from('price_list')
				->where('category_id', $data[$i]->categoryId)	
				->where('ironing_with_starching_rate
				 >', 0)				
				->where('is_active', 1)
				->where('is_deleted',0);
			$dataX = $this->db->get()->result();
			if($dataX){
				$data[$i]->name = $data[$i]->categoryName;
				$data[$i]->item = 	$dataX;	
			}else{
				array_push($unsetKeys,$i);
			}
			
			}  
			for($j=0;$j < sizeof($unsetKeys); $j++){
				$id = $unsetKeys[$j];
				unset($data[$id]); //removing objects by index
			}		
			$data = array_values($data);
			
		}

return (object)['status' => true,'category' => $data];

		}

		
	public function createOrder($userId,$deliveryDate,$pickupDate,$services,$userAddressId){
		date_default_timezone_set('Asia/Kolkata');
		$dateNow = date("Y-m-d H:i:s");
       $serv = '';
			for($i=0;$i<sizeof($services);$i++){
				$id = $services[$i];
				if(strlen($serv) == 0 ){
					$serv = $id;
				}else{
					$serv = $serv.','.$id;
				}
			}

			

		$insert_a = ['user_id' => $userId,'user_address_id' => $userAddressId,'created_at' => $dateNow,'delivery_date' => $deliveryDate,'shop_id' => 1,'pickup_date' => $pickupDate,'services' => $serv];	
		 $this->db->insert('order', $insert_a);
		 $inserted_id3 = $this->db->insert_id();
		if($inserted_id3){
			return (object)['status' => true, 'message' => 'Order created successfully'];
		}
		
		
		return (object)['status' => false, 'message' => "order Already exist"];
		
		}


		public function listUserDetails($userId){
				$this->db
						->select('user_name as userName,mobile_number as mobileNumber')
						->from('user')
						->where('user_id', $userId)
						->where('is_active', 1)
						->where('is_deleted',0);
				$data = $this->db->get()->row();
				if(!$data){
					return (object)['status' => false, 'message' => "something went wrong"];
				 }
				 return (object)['status' =>true, 'user' => $data];
		
				}

/*public function createCustomerMeasurementOrderDetail($userId,$custId,$orderDetail){
	//($work,$userId,$measurements,$items,$custId,$deliveryDate,$voiceInstruction,$description,$status)
		date_default_timezone_set('Asia/Kolkata');
		$dateNow = date("Y-m-d H:i:s");

		$insert_a = ['cust_id' => $custId,'user_id' => $userId,'created_at' => $dateNow];	
		 $this->db->insert('order_master', $insert_a);
		 $inserted_id3 = $this->db->insert_id();

		for($i=0;$i<sizeof($orderDetail);$i++){
			
			$itemId = $orderDetail[$i]->itemId;
			
		 $insert_a = ['cust_id' => $custId,'user_id' => $userId,'item_id' => $itemId,'created_at' => $dateNow ];	
		 $this->db->insert('customer_item', $insert_a);
		 $inserted_id = $this->db->insert_id();
		 
		 $measurements = $orderDetail[$i]->measurements;
		 
		 $isNew = $orderDetail[$i]->isNew;
		 $isNew == 0;

		 if(!$isNew){
			
			for($j=0;$j<sizeof($measurements);$j++){
				$measId = $measurements[$j]->measId;
				$measurement = $measurements[$j]->measurement; 
				$itemId = $orderDetail[$i]->itemId;
				$insert_a = ['cust_id' => $custId,'user_id' => $userId,'item_id' => $itemId,'created_at' => $dateNow,'meas_id' => $measId,'measurements' => $measurement,'customer_item_id' =>$inserted_id  ];	
				$this->db->insert('customer_measurements_detail', $insert_a);
				$inserted_id2 = $this->db->insert_id();
	
		
			}
}


		
			$itemId = $orderDetail[$i]->itemId;
			$description = $orderDetail[$i]->description;
			$pickupDate  = $orderDetail[$i]->pickupDate;
			$deliveryDate = $orderDetail[$i]->deliveryDate;
         $insert_a = ['order_id' => $inserted_id3,'delivery_date' => $deliveryDate,'pickup_date' => $pickupDate,'item_id' => $itemId,'description' => $description,'created_at' => $dateNow,'customer_item_id' =>$inserted_id ];	
		 $this->db->insert('order_item_details_1', $insert_a);
		 $inserted_id4 = $this->db->insert_id();
		 //}
		 $work = $orderDetail[$i]->work;
		 for($k=0;$k<sizeof($work);$k++){
			$workMasterId = $work[$k]->workMasterId;
			$itemId = $orderDetail[$i]->itemId;
			$orderD1Id = $inserted_id4; 
			$insert_a = ['order_d1_id' => $orderD1Id,'order_id' => $inserted_id3,'work_master_id' =>$workMasterId,'item_id' => $itemId];	
			$this->db->insert('order_work_detail', $insert_a);
			$inserted_id5 = $this->db->insert_id();
			$work[$i]->orderD1Id = $inserted_id5;
}
		

	}
		

		 if($inserted_id5){	
			 return (object)['status' => true, 'message' => 'updated successfully','orderId' => $inserted_id3,'workMasterId' =>$workMasterId,'work' =>$work];
		  }
		return (object)['status' => false, 'message' => "something went wrong"];
	} */

	private function seperateComma($services){
		$serArray = explode(",",$services);
		$serName = '';
		for($i=0;$i<sizeof($serArray);$i++){
			$serviceId = $serArray[$i];
			$this->db
				->select('service_name as serviceName')
				->from('service')
				->where('service_id', $serviceId);
				$data = $this->db->get()->row();		
			$serviceName = $data->serviceName;
			
			if(strlen($serName) == 0){
				$serName = $serviceName;
			}else{
				$serName = $serName.','.$serviceName;
			}
		}

		return $serName;
	}
	
	public function listOrder($userId){

		$this->db
		->select('o.services as services,os.status as statusName,o.order_id as orderId,o.user_id as userId,o.pickup_date as pickupDate,o.delivery_date as deliveryDate,o.status as status')
		->from('order as o')
		->join('order_status as os','os.order_status_id = o.status')
		->where('user_id', $userId)		
		->where('o.status <', 2)
        ->where('o.is_active', 1)
	    ->where('o.is_deleted',0);
	 $activeOrders = $this->db->get()->result();

	 for($i=0;$i<sizeof($activeOrders);$i++){
		$serviceName = $this->seperateComma($activeOrders[$i]->services);
		$activeOrders[$i]->serviceName = $serviceName;
		}
	
	$this->db
	->select('o.services as services,os.status as statusName,o.order_id as orderId,o.user_id as userId,o.pickup_date as pickupDate,o.delivery_date as deliveryDate,o.status as status')
		->from('order as o')
		->join('order_status as os','os.order_status_id = o.status')
		->where('user_id', $userId)		
	//status 1 = deliverd orders
	->where('o.status >', 1)
	->where('o.is_active', 1)
	->where('o.is_deleted',0);
	$pastorders = $this->db->get()->result();

	for($i=0;$i<sizeof($pastorders);$i++){
		$serviceName = $this->seperateComma($pastorders[$i]->services);
		$pastorders[$i]->serviceName = $serviceName;
		}
	if(!$pastorders and !$activeOrders){
		return(object)['status' => false, 'message' => "No Orders Found"];
	}
return (object)['status' =>true, 'activeOrders' => $activeOrders , 'pastorders' => $pastorders ];
	
}

public function listOrderStatusDetails(){

	$this->db
	->select('o.order_id as orderId,o.pickup_date as pickupDate,o.delivery_date as deliveryDate,o.created_at as orderDate,u.user_name as userName,ua.user_address as userAddress,ua.google_address as googleAddress,ua.lattitude as lattitude,ua.longitude as longitude')
	->from('order as o')
	->join('user_address as ua','ua.user_address_id = o.user_address_id')
	->join('user as u','u.user_id = o.user_id')
	->where('o.status',0)		
	->where('o.is_active',1)
	->where('o.is_deleted',0);
 $orderStatusDetails = $this->db->get()->result();

if(!$orderStatusDetails){
	return(object)['status' => false, 'message' =>"No orders found"];
}
return (object)['status' =>true,'orderStatusDetails'=>$orderStatusDetails ];
}

public function listOrderStatusDetails1(){

	$this->db
	->select('o.order_id as orderId,o.pickup_date as pickupDate,o.delivery_date as deliveryDate,o.created_at as orderDate,u.user_name as userName,ua.user_address as userAddress,ua.google_address as googleAddress,ua.lattitude as lattitude,ua.longitude as longitude')
	->from('order as o')
	->join('user_address as ua','ua.user_address_id = o.user_address_id')
	->join('user as u','u.user_id = o.user_id')
	->where('o.status',1)		
	->where('o.is_active',1)
	->where('o.is_deleted',0);
 $orderStatusDetails = $this->db->get()->result();

if(!$orderStatusDetails){
	return(object)['status' => false, 'message' =>"No orders found"];
}
return (object)['status' =>true,'orderStatusDetails'=>$orderStatusDetails ];
}

public function listOrderStatusDetails2(){

	$this->db
	->select('o.order_id as orderId,o.pickup_date as pickupDate,o.delivery_date as deliveryDate,o.created_at as orderDate,u.user_name as userName,ua.user_address as userAddress,ua.google_address as googleAddress,ua.lattitude as lattitude,ua.longitude as longitude')
	->from('order as o')
	->join('user_address as ua','ua.user_address_id = o.user_address_id')
	->join('user as u','u.user_id = o.user_id')
	->where('o.status',2)		
	->where('o.is_active',1)
	->where('o.is_deleted',0);
 $orderStatusDetails = $this->db->get()->result();

if(!$orderStatusDetails){
	return(object)['status' => false, 'message' =>"No orders found"];
}
return (object)['status' =>true,'orderStatusDetails'=>$orderStatusDetails ];
}


public function listProducts($search,$serviceId){
	$search = $search.'%';
	if($serviceId == 1){
		$this->db 
				->select('price_list_id as priceListId,item_name as itemName,laundry_rate as rate')
				->from('price_list')
				->where('item_name like', $search)
											
				->where('is_active', 1)
				->where('is_deleted',0);
		$dataX = $this->db->get()->result();
	}elseif($serviceId == 2){//dry_cleaning_rate
		$this->db 
				->select('price_list_id as priceListId,item_name as itemName,dry_cleaning_rate as rate')
				->from('price_list')
			    ->where('item_name like', $search)							
				->where('is_active', 1)
				->where('is_deleted',0);
		$dataX = $this->db->get()->result();
	}
	elseif($serviceId == 3){//ironing_rate
		$this->db 
				->select('price_list_id as priceListId,item_name as itemName,ironing_rate as rate')
				->from('price_list')
			    ->where('item_name like', $search)							
				->where('is_active', 1)
				->where('is_deleted',0);
		$dataX = $this->db->get()->result();
	}
	elseif($serviceId == 4){//shoe_cleaning
		$this->db 
				->select('price_list_id as priceListId,item_name as itemName,shoe_cleaning as rate')
				->from('price_list')
			    ->where('item_name like', $search)							
				->where('is_active', 1)
				->where('is_deleted',0);
		$dataX = $this->db->get()->result();
	}
	elseif($serviceId == 5){//bag_cleaning
		$this->db 
				->select('price_list_id as priceListId,item_name as itemName,bag_cleaning as rate')
				->from('price_list')
			    ->where('item_name like', $search)							
				->where('is_active', 1)
				->where('is_deleted',0);
		$dataX = $this->db->get()->result();
	}
	elseif($serviceId == 6){//commercial
		$this->db 
				->select('price_list_id as priceListId,item_name as itemName,commercial as rate')
				->from('price_list')
			    ->where('item_name like', $search)							
				->where('is_active', 1)
				->where('is_deleted',0);
		$dataX = $this->db->get()->result();
	}
	elseif($serviceId == 7){//ironing_with_starching_rate
		$this->db 
				->select('price_list_id as priceListId,item_name as itemName,ironing_with_starching_rate as rate')
				->from('price_list')
			    ->where('item_name like', $search)							
				->where('is_active', 1)
				->where('is_deleted',0);
		$dataX = $this->db->get()->result();
	}
	


if(!$dataX){
	return(object)['status' => false, 'message' =>"something went wrong"];
}
return (object)['status' =>true,'Products'=>$dataX ];
}


public function createOrderDetails($orderDetail,$orderId,$subTotal){


	//var_dump($orderDetail);
	for($i=0;$i<sizeof($orderDetail);$i++){
		$serviceId = $orderDetail[$i]->serviceId;
		$priceListId = $orderDetail[$i]->priceListId;
		$quantity = $orderDetail[$i]->quantity;
		$amount = $orderDetail[$i]->amount;
		//echo($amount);
		$insert_a = ['order_id' => $orderId,'service_id' => $serviceId,'price_list_id' => $priceListId,'quantity' => $quantity,'amount' => $amount];	
		$this->db->insert('order_details', $insert_a);
		$inserted_id3 = $this->db->insert_id();
	
	}
	$update_a = ['status' => '1','order_amount' => $subTotal];
	$this->db->where('order_id', $orderId);
	$this->db->update('order', $update_a);
	$update_res = $this->db->affected_rows();
	return (object)['status' => true, 'message' => 'Order Details Created Successfully'];
 // return (object)['status' => false, 'message' => "Something Went Wrong Again"];
	}


	public function listOrderDetails($orderId){
		$this->db
				->select('pl.item_name as itemName,od.quantity as quantity,od.amount as amount,se.service_name as serviceName')
				->from('order_details as od')
	            ->join('order as o','o.order_id = od.order_id')
				->join('price_list as pl','pl.price_list_id = od.price_list_id')
				->join('service as se','se.service_id = od.service_id')
				->where('od.order_id', $orderId)
			    ->where('od.is_active', 1)
			    ->where('od.is_deleted',0);
		$orderDetails = $this->db->get()->result();


	$this->db
				->select('order_amount as subTotal')
				->from('order')
	            ->where('order_id', $orderId)
			    ->where('is_active', 1)
				->where('is_deleted',0);
				$subTotal = $this->db->get()->result();
				

		if(!$orderDetails){
			return (object)['status' => false, 'message' => "something went wrong"];
		 }
		 return (object)['status' =>true, 'orderStatus' => $orderDetails, 'subTotal' => $subTotal];
	
		}
		
	public function completeOrder($orderId){

	$update_a = ['status' => '2'];
	$this->db->where('order_id', $orderId);
	$this->db->update('order', $update_a);
	$update_res = $this->db->affected_rows();
	if(!$update_res){
		return (object)['status' => false, 'message' => "something went wrong"];
	 }


	return (object)['status' => true, 'message' => 'Order completed Successfully'];

		}












public function listOrderStatus(){
	$this->db
			->select('order_status_id as orderStatusId,status as statusName')
			->from('order_status')
			->where('is_active', 1)
			->where('order_status_id <', 3)
			
			->where('is_deleted',0);
	$orderStatus = $this->db->get()->result();
	if(!$orderStatus){
		return (object)['status' => false, 'message' => "something went wrong"];
	 }
	 return (object)['status' =>true, 'orderStatus' => $orderStatus];

	}
















	
	public function payment($orderId,$work,$itemId){
                $totalAmount = 0.00;
				for($i=0;$i<sizeof($work);$i++){
					$orderWorkDetailId = $work[$i]->orderWorkDetailId;
					$amount= $work[$i]->amount;
					

					
					
		      $update_a = ['amount' =>$amount];
			  $this->db->where('order_work_detail_id', $orderWorkDetailId);
		      $this->db->update('order_work_detail', $update_a);
			  $update_res = $this->db->affected_rows();
			  $totalAmount = $totalAmount+$amount;
				 
			  $update_a = ['is_active' => '1','order_amount' => $totalAmount];
			  $this->db->where('order_id', $orderId);
		      $this->db->update('order_master', $update_a);
		      $update_res = $this->db->affected_rows();
			
		}


				
			 
		    if(!$update_res){	
			return (object)['status' => false, 'message' => "something went wrong2"];

		   }
		   return (object)['status' => true, 'message' => 'updated successfully'];
		}


	
public function listPayment($orderId,$custId){

                $this->db
		            ->select('oid1.customer_item_id as customerItemId,oid1.order_d1_id as orderD1Id,oid1.delivery_date as deliveryDate,oid1.voice_instruction as voiceInstruction,oid1.description as description,oid1.created_at as createdAt,oid1.is_active as isActive,im.item_name as itemName,im.is_active as isActive')
					->from('order_item_details_1 as oid1')
					->join('item_master as im','im.item_id = oid1.item_id')
					->where('oid1.order_id', $orderId);
					 
			$orderWorkDetail = $this->db->get()->result();
			 
			for($i=0;$i<sizeof($orderWorkDetail);$i++){
				$orderD1Id = $orderWorkDetail[$i]->orderD1Id;
				$this->db
				->select('owd.order_work_detail_id as orderWorkDetailId,owd.work_master_id as workMasterId,owd.item_id as itemId,owd.is_active as isActive,owd.amount as amount,wm.work as work')					
				->from('order_work_detail as owd')
				->join('work_master as wm','wm.work_master_id = owd.work_master_id')
				->where('owd.order_d1_id',$orderD1Id);
				
    
	$data_detail = $this->db->get()->result();
	$orderWorkDetail[$i]->itemVendorDetail = $data_detail;
	
}
	$this->db
	->select('name as name,mobile_number as mobileNumber')
	->from('customer_vendor')
	->where('cust_id', $custId)
	->where('is_active', 1)
	->where('is_deleted',0);
$customerVendor = $this->db->get()->row();
if($customerVendor){
return (object)['status' => true,'orderWorkDetail' => $orderWorkDetail,'userDetails' => $customerVendor ];
}		       
			
}

public function getjobcount($userId){
date_default_timezone_set('Asia/Kolkata');
		$dateNow = date("Y-m-d");

$this->db
	->select('job_table_id as jobTableId ,job_status as jobStatus,is_active as isActive')
	->from('job_master')	
	->where('is_active', 1)
	->where('is_deleted',0);
	$jobs = $this->db->get()->result();
	
for($i=0;$i<sizeof($jobs);$i++){
	$jobTableId= $jobs[$i]->jobTableId;
		

if($jobTableId==1){//pending
	$this->db
	->select('count(order_id) as count')
	->from('order_master')
	->where('user_id', $userId)
    ->where('order_status',0);
    //->where('is_active', 1)
	//->where('is_deleted',0);
$pendingJobs= $this->db->get()->row();

$jobs[$i]->count = $pendingJobs->count;

}

elseif($jobTableId==2){//thisWeek
	$weekstart = strtotime("next Monday") - 604800;
	$this->db
		->select('count(oid1.order_id) as count')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->where('ci.user_id', $userId)
		->where('oid1.delivery_date >', $weekstart);
		
	$thisWeek= $this->db->get()->row();
	$jobs[$i]->count = $thisWeek->count;
	
	//$jobs[$i]->count = 0;
	}
elseif($jobTableId==3){//comingWeek
	$weekend = strtotime("next Monday") - 1;
	$this->db
	->select('count(oid1.order_id) as count')
	->from('order_item_details_1 as oid1')
	->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
	->where('ci.user_id', $userId)
	->where('oid1.delivery_date >', $weekend);
	
$comingWeek= $this->db->get()->row();
$jobs[$i]->count = $comingWeek->count;
//$jobs[$i]->count = 0;

}
elseif($jobTableId==4){//thisMonth
	$lastDate = strtotime('next Month');

	$this->db
		->select('count(oid1.order_id) as count')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->where('ci.user_id', $userId)
		->where('oid1.delivery_date >', $lastDate);
		
	$thisMonth= $this->db->get()->row();
	$jobs[$i]->count = $thisMonth->count;

	//$jobs[$i]->count = 0;
}
elseif($jobTableId==5){//due
$this->db
		->select('count(oid1.order_id) as count')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->where('ci.user_id', $userId)
		->where('oid1.delivery_date <', $dateNow);
		//->where('oid1.is_active', 1)
		//->where('oid1.is_deleted',0);
	$due= $this->db->get()->row();
	$jobs[$i]->count = $due->count;
	}
	

elseif($jobTableId==6){//finished
$this->db
	->select('count(order_id) as count')
	->from('order_master')
	->where('user_id', $userId)
	->where('order_status',1);
    //->where('is_active', 1)
	//->where('is_deleted',0);
	$finished= $this->db->get()->row();
$jobs[$i]->count = $finished->count;

}
elseif($jobTableId==7){//delivered
$this->db
	->select('count(order_id) as count')
	->from('order_master')
	->where('user_id', $userId)
    ->where('order_status',2);
    //->where('is_active', 1)
	//->where('is_deleted',0);
$delivered= $this->db->get()->row();
$jobs[$i]->count = $delivered->count;
}
}


$this->db
->select('user_id as userId,name as name,business_name as businessName,is_active as isActive')
->from('user')
->where('user_id', $userId);

$user = $this->db->get()->result();
for($i=0;$i<sizeof($user);$i++){
	$userId= $user[$i]->userId;
	$weekstart = strtotime("next Monday") - 604800;
	$this->db
		->select('count(oid1.order_id) as thisWeek')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->where('ci.user_id', $userId)
		->where('oid1.delivery_date >', $weekstart);
		
	$thisWeek= $this->db->get()->row();
	
	$user[$i]->thisWeek = $thisWeek->thisWeek;

	$lastDate = strtotime('next Month');

	$this->db
		->select('count(oid1.order_id) as thisMonth')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->where('ci.user_id', $userId)
		->where('oid1.delivery_date >', $lastDate);
		
	$thisMonth= $this->db->get()->row();
	
	$user[$i]->thisMonth = $thisMonth->thisMonth;

	$this->db
		->select('count(oid1.order_id) as today')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->where('ci.user_id', $userId)
		->where('oid1.delivery_date =', $dateNow);
		
	$today= $this->db->get()->row();
	
	$user[$i]->today = $today->today;
	



}




		
return (object)['status' => true,'jobs' => $jobs,'user' => $user];





}

public function listJobItems($userId,$jobTableId){
	date_default_timezone_set('Asia/Kolkata');
	$dateNow = date("Y-m-d");
	
	if($jobTableId == 1){//pending
		$this->db
			->select('om.order_id as orderId,om.cust_id as custId,om.is_active as isActive,cv.name as name')
			->from('order_master as om')
			->join('customer_vendor as cv','cv.cust_id = om.cust_id')
			->where('om.user_id', $userId)
			->where('om.order_status',0);
			//->where('om.is_active', 1)
			//->where('om.is_deleted',0)
	$orders= $this->db->get()->result();
	
	for($i=0;$i<sizeof($orders);$i++){
	$orderId = $orders[$i]->orderId;
	
	$this->db
		    ->select('oid1.delivery_date as deliveryDate,im.item_name as itemName')					
			->from('order_item_details_1 as oid1')
			->join('item_master as im','im.item_id = oid1.item_id')
		    //->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')				
			->where('oid1.order_id',$orderId);
			$data1= $this->db->get()->row();
			$orders[$i]->orderDetail = $data1;
	}
	}
	if($jobTableId == 2){//this week
		$weekstart = strtotime("next Monday") - 604800; 
     $this->db
		->select('oid1.delivery_date as deliveryDate,oid1.is_active as isActive,oid1.order_id as orderId,oid1.item_id as itemId,cv.cust_id as custId,cv.name as name')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->join('customer_vendor as cv','cv.cust_id = ci.cust_id')
		->where('cv.user_id', $userId)
		->where('oid1.delivery_date >', $weekstart);
		//->where('om.is_active', 1)
		//->where('om.is_deleted',0);
$orders= $this->db->get()->result();


for($i=0;$i<sizeof($orders);$i++){
	$orderId = $orders[$i]->orderId;
	
	$this->db
		    ->select('oid1.delivery_date as deliveryDate,im.item_name as itemName')					
			->from('order_item_details_1 as oid1')
			->join('item_master as im','im.item_id = oid1.item_id')
		    //->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')				
			->where('oid1.order_id',$orderId);
			$data1= $this->db->get()->row();
			$orders[$i]->orderDetail = $data1;
	}
	}

	if($jobTableId == 3){//comming week
		$weekend = strtotime("next Monday") - 1;
		$this->db
		->select('oid1.delivery_date as deliveryDate,oid1.is_active as isActive,oid1.order_id as orderId,oid1.item_id as itemId,cv.cust_id as custId,cv.name as name')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->join('customer_vendor as cv','cv.cust_id = ci.cust_id')
		->where('cv.user_id', $userId)
		->where('oid1.delivery_date >', $weekend);
		//->where('om.is_active', 1)
		//->where('om.is_deleted',0);
$orders= $this->db->get()->result();


for($i=0;$i<sizeof($orders);$i++){
	$orderId = $orders[$i]->orderId;
	
	$this->db
		    ->select('oid1.delivery_date as deliveryDate,im.item_name as itemName')					
			->from('order_item_details_1 as oid1')
			->join('item_master as im','im.item_id = oid1.item_id')
		    //->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')				
			->where('oid1.order_id',$orderId);
			$data1= $this->db->get()->row();
			$orders[$i]->orderDetail = $data1;
	}
	}

	if($jobTableId == 4){//this month
		$lastDate = strtotime('next Month');

		$this->db
		->select('oid1.delivery_date as deliveryDate,oid1.is_active as isActive,oid1.order_id as orderId,oid1.item_id as itemId,cv.cust_id as custId,cv.name as name')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->join('customer_vendor as cv','cv.cust_id = ci.cust_id')
		->where('cv.user_id', $userId)
		->where('oid1.delivery_date >', $lastDate);
		//->where('om.is_active', 1)
		//->where('om.is_deleted',0);
$orders= $this->db->get()->result();


for($i=0;$i<sizeof($orders);$i++){
	$orderId = $orders[$i]->orderId;
	
	$this->db
		    ->select('oid1.delivery_date as deliveryDate,im.item_name as itemName')					
			->from('order_item_details_1 as oid1')
			->join('item_master as im','im.item_id = oid1.item_id')
		    //->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')				
			->where('oid1.order_id',$orderId);
			$data1= $this->db->get()->row();
			$orders[$i]->orderDetail = $data1;
	}
	}
	if($jobTableId == 5){//due
		$this->db
		->select('oid1.delivery_date as deliveryDate,oid1.is_active as isActive,oid1.order_id as orderId,oid1.item_id as itemId,cv.cust_id as custId,cv.name as name')
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->join('customer_vendor as cv','cv.cust_id = ci.cust_id')
		->where('cv.user_id', $userId)
		->where('oid1.delivery_date <', $dateNow);
		//->where('om.is_active', 1)
		//->where('om.is_deleted',0);
$orders= $this->db->get()->result();

for($i=0;$i<sizeof($orders);$i++){
	$orderId = $orders[$i]->orderId;		

			$this->db
		    ->select('oid1.delivery_date as deliveryDate,im.item_name as itemName')					
			->from('order_item_details_1 as oid1')
			->join('item_master as im','im.item_id = oid1.item_id')
		    //->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')				
			->where('oid1.order_id',$orderId);
			$data1= $this->db->get()->row();
			$orders[$i]->orderDetail = $data1;
			}

	}

	if($jobTableId == 6){//finished
		$this->db
		    ->select('om.order_id as orderId,om.cust_id as custId,om.is_active as isActive,cv.name as name')
			->from('order_master as om')
			->join('customer_vendor as cv','cv.cust_id = om.cust_id')
			->where('om.user_id', $userId)
			->where('om.order_status',1);
			//->where('om.is_active', 1)
			//->where('om.is_deleted',0)
	$orders= $this->db->get()->result();
	
	for($i=0;$i<sizeof($orders);$i++){
		$orderId = $orders[$i]->orderId;
		
		$this->db
				->select('oid1.delivery_date as deliveryDate,im.item_name as itemName')					
				->from('order_item_details_1 as oid1')
				->join('item_master as im','im.item_id = oid1.item_id')
				//->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')				
				->where('oid1.order_id',$orderId);
				$data1= $this->db->get()->row();
				$orders[$i]->orderDetail = $data1;
		}
	
	}
	if($jobTableId == 7){//delivered
		$this->db
            ->select('om.order_id as orderId,om.cust_id as custId,om.is_active as isActive,cv.name as name')
			->from('order_master as om')
			->join('customer_vendor as cv','cv.cust_id = om.cust_id')
			->where('om.user_id', $userId)
			->where('om.order_status',2);
			//->where('om.is_active', 1)
			//->where('om.is_deleted',0)
	$orders= $this->db->get()->result();
	
	for($i=0;$i<sizeof($orders);$i++){
		$orderId = $orders[$i]->orderId;
		
		$this->db
				->select('oid1.delivery_date as deliveryDate,im.item_name as itemName')					
				->from('order_item_details_1 as oid1')
				->join('item_master as im','im.item_id = oid1.item_id')
				//->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')				
				->where('oid1.order_id',$orderId);
				$data1= $this->db->get()->row();
				$orders[$i]->orderDetail = $data1;
		}
	}

	if(!$orders){
		return (object)['status' => false, 'message' => "No order found"];
	 }
	 return (object)['status' =>true, 'orderDetail' => $orders];

	
}



public function listJobDetails($orderId){
	
$this->db
		->select('oid1.order_d1_id as orderD1Id,oid1.item_id as itemId,oid1.customer_item_id as customerItemId,oid1.delivery_date as deliveryDate,oid1.sample_dress as sampleDress,oid1.voice_instruction as voiceInstruction,oid1.description as description,oid1.created_at as createdAt,oid1.is_active as isActive,cv.name as name')
		
		->from('order_item_details_1 as oid1')
		->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
		->join('customer_vendor as cv','cv.cust_id = ci.cust_id')
		//->where('cv.user_id', $userId)
		->where('oid1.order_id', $orderId);
		//->where('om.is_active', 1)
		//->where('om.is_deleted',0);
$orders= $this->db->get()->result();

for($i=0;$i<sizeof($orders);$i++){
	$itemId = $orders[$i]->itemId;
		
$this->db
->select('item_id as itemId,item_name as itemName')
->from('item_master')
->where('item_id', $itemId)
->where('is_active', 1)
->where('is_deleted',0);
$orderItemDetails1 = $this->db->get()->result();
$orders[$i]->orderItemDetails1 = $orderItemDetails1;
if(!$orders){
   return (object)['status' => false, 'message' => "something went wrong2"];
}
$customerItemId = $orders[$i]->customerItemId; 	

$this->db
	   ->select('cmd.measurements as measurements,me.measurement_name')					
	   ->from('customer_measurements_detail as cmd')
	   ->join('measurement as me','me.meas_id = cmd.meas_id')					
	   ->where('cmd.customer_item_id',$customerItemId);
   $custItemMeasurement = $this->db->get()->result();	
   $orders[$i]->custItemMeasurement = $custItemMeasurement;

	$orderD1Id = $orders[$i]->orderD1Id;
	$this->db
	->select('owd.amount as amount,wm.work as work')					
	->from('order_work_detail as owd')
	->join('work_master as wm','wm.work_master_id = owd.work_master_id')
	->where('owd.order_d1_id',$orderD1Id);
	$workdetail= $this->db->get()->result();
	$orders[$i]->workdetail = $workdetail;	
	
}
return (object)['status' => true,'order' => $orders];
}
public function test($orderId){
	
	date_default_timezone_set('Asia/Kolkata');
	$dateNow = date("Y-m-d");
	$weekstart = strtotime("next Monday") - 604800;
	$this->db
	->select('oid1.delivery_date as deliveryDate,oid1.is_active as isActive,oid1.order_id as orderId,oid1.item_id as itemId,cv.cust_id as custId,cv.name as name')
	->from('order_item_details_1 as oid1')
	->join('customer_item as ci','ci.customer_item_id = oid1.customer_item_id')
	->join('customer_vendor as cv','cv.cust_id = ci.cust_id')
	->where('oid1.order_id', $orderId)
	->where('oid1.delivery_date >', $weekstart);
	//->where('om.is_active', 1)
	//->where('om.is_deleted',0);
$orders= $this->db->get()->result();

return (object)['status' => true,'orders' => $orders];
}


public function createGalleryFolder($userId,$folderName){
	date_default_timezone_set('Asia/Kolkata');
	$dateNow = date("Y-m-d");
	$insert_a = ['folder_name' => $folderName,'user_id' => $userId,'created_at' => $dateNow];	
	$this->db->insert('gallery_folder', $insert_a);
	$galleryFolder = $this->db->insert_id();
	
	return (object)['status' => true,'message' => 'updated successfully'];
}


public function listGalleryFolder($userId){
	$this->db
	->select('gallery_table_id as galleryTableId,folder_name as folderName ')
	->from('gallery_folder')
	->where('user_id', $userId)
	->where('is_active', 1)
	->where('is_deleted',0);
	$galleryFolder = $this->db->get()->result();
	return (object)['status' => true,'galleryFolder' => $galleryFolder];
}
public function uploadImage($galleryTableId,$productImage,$userId){
	
	$dateNow = date("Y-m-d H:i:s");	
	$timestamp = strtotime($dateNow);
	$rand = $this->common->otpGenerator(6);
	$projectPath = FCPATH;
	$path = "/var/www/html/stich/uploads/gallery";
	$path = $projectPath."/uploads/gallery/";
	$productImageName = $rand.$timestamp.".jpeg";
	$filename = $path.$productImageName;
	
	$this->common->base64ToImage($productImage, $filename);
			
	$file_url = $this->s3_upload->upload_file($filename);
	//echo("123");
	//echo($file_url);
	unlink($filename);
	//die();


$insert_a = ['gallery_table_id' => $galleryTableId,'product_image' => $file_url,'user_id' => $userId];	
	$this->db->insert('gallery_details', $insert_a);
	$galleryDetails = $this->db->insert_id();
	
	return (object)['status' => true,'message' => 'updated successfully'];
}


public function listGalleryDetails($galleryTableId){
	$this->db
	->select('product_image as productImage')
	->from('gallery_details')
	->where('gallery_table_id', $galleryTableId)
	->where('is_active', 1)
	->where('is_deleted',0);
	$galleryDetails = $this->db->get()->result();
	return (object)['status' => true,'galleryDetails' => $galleryDetails];
}








	



			


		














private function sendOtp($mobile,$otp){
		
			$curl = curl_init();
			$url = "http://2factor.in/API/V1/3460c6c7-acf6-11e9-ade6-0200cd936042/SMS/".$mobile."/".$otp."/ch_prod";
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded"
			  ),
			));
		
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
		
			if ($err) {
			  return (object)['status' => false, 'data' => $err];
			} else {
			   return (object)['status' => true, 'data' => $response];
			}
}
		



}


/*{
	public function createCustomerMeasurementOrderDetail($work,$userId,$measurements,$itemId,$custId,$deliveryDate,$voiceInstruction,$description,$status){
			
		date_default_timezone_set('Asia/Kolkata');
		$dateNow = date("Y-m-d H:i:s");

		
		 $insert_a = ['cust_id' => $custId,'user_id' => $userId,'item_id' => $itemId,'created_at' => $dateNow ];	
		 $this->db->insert('customer_item', $insert_a);
		 $inserted_id = $this->db->insert_id();
		 
		for($i=0;$i<sizeof($measurements);$i++){
			$measId = $measurements[$i]->measId;
			$measurement = $measurements[$i]->measurement; 
			$insert_a = ['cust_id' => $custId,'user_id' => $userId,'item_id' => $itemId,'created_at' => $dateNow,'meas_id' => $measId,'measurements' => $measurement,'customer_item_id' =>$inserted_id  ];	
			$this->db->insert('customer_measurements_detail', $insert_a);
			$inserted_id2 = $this->db->insert_id();

		}
		 $insert_a = ['cust_id' => $custId,'user_id' => $userId,'created_at' => $dateNow];	
		 $this->db->insert('order_master', $insert_a);
		 $inserted_id3 = $this->db->insert_id();

         $insert_a = ['order_id' => $inserted_id3,'delivery_date' => $deliveryDate,'item_id' => $itemId,'voice_instruction' => $voiceInstruction,'description' => $description,'status' => $status,'created_at' => $dateNow,'customer_item_id' =>$inserted_id ];	
		 $this->db->insert('order_item_details_1', $insert_a);
		 $inserted_id4 = $this->db->insert_id();

		 for($i=0;$i<sizeof($work);$i++){
			$workMasterId = $work[$i]->workMasterId;
			$orderD1Id = $inserted_id4; 
			$insert_a = ['order_d1_id' => $orderD1Id,'order_id' => $inserted_id3,'work_master_id' =>$workMasterId,'item_id' => $itemId];	
			$this->db->insert('order_work_detail', $insert_a);
			$inserted_id5 = $this->db->insert_id();
			$work[$i]->orderD1Id = $inserted_id5;


		}
		


		

		 if($inserted_id5){	
			 return (object)['status' => true, 'message' => 'updated successfully','orderId' => $inserted_id3,'workMasterId' =>$workMasterId,'work' =>$work];
		  }
		return (object)['status' => false, 'message' => "something went wrong"];
	}		 

}*/

/*$weekstart = strtotime("next Monday") - 604800; 
 
echo "start of week is: ".date("D M j G:i:s T Y", $weekstart)."\n"; 

$weekend = strtotime("next Monday") - 1;
 
echo "start of week is: ".date("D M j G:i:s T Y", $weekend)."\n"; */