<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	
   
	public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Methods: GET, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();		
		$this->load->model('Login_model');
        
    }

    public function login(){
       
        $json_obj = $this->readJson();
        $mobileNumber = isset($json_obj->mobileNumber) ? trim($json_obj->mobileNumber): '';	
        
       
		if( !$mobileNumber ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'mobileNumber is blank']);     
        }	
        
       
        $this->result = $this->Login_model->login($mobileNumber);
        $this->jsonOutput($this->result);
    }

    public function loginVendor(){
       
        $json_obj = $this->readJson();
        $userNameVendor = isset($json_obj->userNameVendor) ? trim($json_obj->userNameVendor): '';	
        $passwordVendor = isset($json_obj->passwordVendor) ? trim($json_obj->passwordVendor): '';	
       
		if( !$userNameVendor ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userNameVendor is blank']);     
        }	
           
		if( !$passwordVendor ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'passwordVendor is blank']);     
        }	
        
        
       
        $this->result = $this->Login_model->loginVendor($userNameVendor,$passwordVendor);
        $this->jsonOutput($this->result);
    }

    public function listProducts(){
       
        $json_obj = $this->readJson();
        $search = isset($json_obj->search) ? trim($json_obj->search): '';	
        $serviceId = isset($json_obj->serviceId) ? trim($json_obj->serviceId): '';	
       
		if( !$search ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'search is blank']);     
        }	
           
		if( !$serviceId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'serviceId is blank']);     
        }	
        
        
       
        $this->result = $this->Login_model->listProducts($search,$serviceId);
        $this->jsonOutput($this->result);
    }





    public function listOrderStatus(){
       
        $json_obj = $this->readJson();
        
       
        $this->result = $this->Login_model->listOrderStatus();
        $this->jsonOutput($this->result);
    }


    
    
    public function listOrderStatusDetails(){
       
        $json_obj = $this->readJson();
    
        $this->result = $this->Login_model->listOrderStatusDetails();
        $this->jsonOutput($this->result);
    }

        
    public function listOrderStatusDetails1(){
       
        $json_obj = $this->readJson();
    
        $this->result = $this->Login_model->listOrderStatusDetails1();
        $this->jsonOutput($this->result);
    }

          
    public function listOrderStatusDetails2(){
       
        $json_obj = $this->readJson();
    
        $this->result = $this->Login_model->listOrderStatusDetails2();
        $this->jsonOutput($this->result);
    }


    public function createOrderDetails(){
       
      $json_obj = $this->readJson();
        $orderId = isset($json_obj->orderId) ? trim($json_obj->orderId): '';	
        //$serviceId = isset($json_obj->serviceId) ? trim($json_obj->serviceId): '';	
        //$priceListId = isset($json_obj->priceListId) ? trim($json_obj->priceListId): '';	
       // $quantity = isset($json_obj->quantity) ? trim($json_obj->quantity): '';	
        $subTotal = isset($json_obj->subTotal) ? trim($json_obj->subTotal): '';	
      $orderDetail = $json_obj->orderDetail;  
      if( !$orderId ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'orderId is blank']);     
    }  if( !$subTotal ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'subTotal is blank']);     
    }
      
      
       
        $this->result = $this->Login_model->createOrderDetails($orderDetail,$orderId,$subTotal);
        $this->jsonOutput($this->result);
    }

    public function listOrderDetails(){
       
        $json_obj = $this->readJson();
          $orderId = isset($json_obj->orderId) ? trim($json_obj->orderId): '';	
         
          if( !$orderId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'orderId is blank']);     
        }
    
          $this->result = $this->Login_model->listOrderDetails($orderId);
          $this->jsonOutput($this->result);
      }
      
      public function  completeOrder() {
       
        $json_obj = $this->readJson();
          $orderId = isset($json_obj->orderId) ? trim($json_obj->orderId): '';	
         
          if( !$orderId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'orderId is blank']);     
        }
    
          $this->result = $this->Login_model-> completeOrder($orderId); 
          $this->jsonOutput($this->result);
      }

    
	


    public function validateOtp(){
       
        $json_obj = $this->readJson();
        $mobileNumber = isset($json_obj->mobileNumber) ? trim($json_obj->mobileNumber): '';
        $otp = isset($json_obj->otp) ? trim($json_obj->otp): '';
       			
		if( !$mobileNumber ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'mobileNumber is blank']);     
        }
        if( !$otp ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'otp is blank']);     
        }		
        $this->result = $this->Login_model->validateOtp($mobileNumber,$otp);
        $this->jsonOutput($this->result);
    }

    
    public function signup(){
       
        $json_obj = $this->readJson();
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
        $userName = isset($json_obj->userName) ? trim($json_obj->userName): '';
        $address = isset($json_obj->address) ? trim($json_obj->address): '';
        $landmark = isset($json_obj->landmark) ? trim($json_obj->landmark): '';
        if( !$userId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
        }
       			
		if( !$userName ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userName is blank']);     
        }
        if( !$address ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'address is blank']);     
        }
        if( !$landmark ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'landmark is blank']);     
        }
        
        $this->result = $this->Login_model->signup($userName,$userId,$address,$landmark);
        $this->jsonOutput($this->result);
    }

    public function listShop(){
       
        $json_obj = $this->readJson();
        

        $this->result = $this->Login_model->listShop();
        $this->jsonOutput($this->result);
        
    }

    public function listService(){
        $json_obj = $this->readJson();
        
        
        $this->result = $this->Login_model->listService();
        $this->jsonOutput($this->result);

    } 

    public function createAddress(){
        
        $json_obj = $this->readJson();
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
        $userAddress = isset($json_obj->userAddress) ? trim($json_obj->userAddress): '';
        $lattitude = isset($json_obj->lattitude) ? trim($json_obj->lattitude): '';
        $longitude = isset($json_obj->longitude) ? trim($json_obj->longitude): '';
        $landmark = isset($json_obj->landmark) ? trim($json_obj->landmark): '';
        $saveAs = isset($json_obj->saveAs) ? trim($json_obj->saveAs): '';
        $googleAddress = isset($json_obj->googleAddress) ? trim($json_obj->googleAddress): '';
     

        if( !$userId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
        }	
    
        if( !$userAddress ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userAddress is blank']);     
        }	
        if( !$lattitude ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'lattitude is blank']);     
        }	
    
        if( !$longitude ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'longitude is blank']);     
        }	
        if( !$landmark ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'landmark is blank']);     
        }	
    
        if( !$saveAs ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'saveAs is blank']);     
        }	
       
        
    $this->result = $this->Login_model->createAddress($userId,$userAddress,$lattitude,$longitude,$landmark,$saveAs,$googleAddress);
    $this->jsonOutput($this->result);

    } 
    public function listAddresses(){
        $json_obj = $this->readJson();
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
        if( !$userId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
        }	
    
        $this->result = $this->Login_model-> listAddresses($userId);
        $this->jsonOutput($this->result);

    } 
    public function listAddressesTag(){
        $json_obj = $this->readJson();
        $userAddressId = isset($json_obj->userAddressId) ? trim($json_obj->userAddressId): '';
        if( !$userAddressId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userAddressId is blank']);     
        }	
    
        $this->result = $this->Login_model-> listAddressesTag($userAddressId);
        $this->jsonOutput($this->result);

    } 


    public function createShopItems(){
        
        $json_obj = $this->readJson();
        $shopId = isset($json_obj->shopId) ? trim($json_obj->shopId): '';
        $serviceId = isset($json_obj->serviceId) ? trim($json_obj->serviceId): '';

        if( !$shopId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'shopId is blank']);     
        }	
    
        if( !$serviceId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'serviceId is blank']);     
        }	
        $this->result = $this->Login_model->createShopItems($shopId,$serviceId);
        $this->jsonOutput($this->result);

    } 

    public function listShopServices(){
       
        $json_obj = $this->readJson();
        $shopId = isset($json_obj->shopId) ? trim($json_obj->shopId): '';
        

        if( !$shopId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'shopId is blank']);     
        }
        
        $this->result = $this->Login_model->listShopServices($shopId);
        $this->jsonOutput($this->result);

    } 
    public function createPincodeDetails(){
        
        $json_obj = $this->readJson();
        $shopId = isset($json_obj->shopId) ? trim($json_obj->shopId): '';
        $pincode = isset($json_obj->pincode) ? trim($json_obj->pincode): '';
        $pincodeStatus = isset($json_obj->pincodeStatus) ? trim($json_obj->pincodeStatus): '';
        
        if( !$shopId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'shopId is blank']);     
        } 
        if( !$pincode){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'pincode is blank']);     
        }	
        if( !$pincodeStatus ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'pincodeStatus is blank']);     
        }	
        
        
        $this->result = $this->Login_model->createPincodeDetails($shopId,$pincode,$pincodeStatus);
        $this->jsonOutput($this->result);
    }
    
    public function listPincodeDetails(){
        $json_obj = $this->readJson();
        $shopId = isset($json_obj->shopId ) ? trim($json_obj->shopId ): '';
       
        
        if( !$shopId ){
            $this->jsonOutput(['status'=> 'fail', 'message' => 'shopId  is blank']);     
        } 
      

        $this->result = $this->Login_model->listPincodeDetails($shopId);
        $this->jsonOutput($this->result);
    }
    public function listPriceList(){
       
        $json_obj = $this->readJson();
        $serviceId = isset($json_obj->serviceId ) ? trim($json_obj->serviceId ): '';
       
        
        if( !$serviceId ){
            $this->jsonOutput(['status'=> 'fail', 'message' => 'serviceId  is blank']);     
        } 
        
        $this->result = $this->Login_model->listPriceList($serviceId);
        $this->jsonOutput($this->result);
        

    } 
    public function listUserDetails(){
       
        $json_obj = $this->readJson();
        $userId = isset($json_obj->userId ) ? trim($json_obj->userId ): '';
       
        
        if( !$userId ){
            $this->jsonOutput(['status'=> 'fail', 'message' => 'userId  is blank']);     
        } 
        $this->result = $this->Login_model->listUserDetails($userId);
        $this->jsonOutput($this->result);

    } 

    public function createOrder(){
        
        $json_obj = $this->readJson();
       
      
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
        $services = $json_obj->services;       
        $deliveryDate = isset($json_obj->deliveryDate) ? trim($json_obj->deliveryDate): '';

        $pickupDate = isset($json_obj->pickupDate) ? trim($json_obj->pickupDate): '';
        $userAddressId = isset($json_obj->userAddressId) ? trim($json_obj->userAddressId): '';     
  
        if( !$userId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
        } 
        if( !$deliveryDate ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'deliveryDate is blank']);     
        }
        if( !$pickupDate ){
            $this->jsonOutput(['status'=> 'fail', 'message' => 'pickupDate is blank']);    
         }
        if( !$userAddressId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userAddressId is blank']);     
        } 
             
      $this->result = $this->Login_model->createOrder($userId,$deliveryDate,$pickupDate,$services,$userAddressId);
        $this->jsonOutput($this->result);
    }
    
    public function listOrder(){
        $json_obj = $this->readJson();
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
       if( !$userId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
        } 
        
        $this->result = $this->Login_model->listOrder($userId);
        $this->jsonOutput($this->result);
        

    }
     public function payment(){
        $json_obj = $this->readJson();
        $orderId = isset($json_obj->orderId) ? trim($json_obj->orderId): '';
        
        $work = $json_obj->work;
        $itemId = isset($json_obj->itemId) ? trim($json_obj->itemId): '';
     
        
        if( !$orderId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'orderId is blank']);     
        } 
       
        if( !$itemId  ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'itemId  is blank']);     
        }  
        
        $this->result = $this->Login_model->payment($orderId,$work,$itemId);
        $this->jsonOutput($this->result);
        
    }
    public function listPayment(){
        $json_obj = $this->readJson();
        $orderId = isset($json_obj->orderId) ? trim($json_obj->orderId): '';
        $custId = isset($json_obj->custId) ? trim($json_obj->custId): '';
        
        if( !$orderId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'orderId is blank']);     
        } 
        if( !$custId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'custId is blank']);     
        }
          
        $this->result = $this->Login_model->listPayment($orderId,$custId);
        $this->jsonOutput($this->result);
        
    }
    public function listJobConfirmation(){
        $json_obj = $this->readJson();

        $this->result = $this->Login_model->listJobConfirmation();
        $this->jsonOutput($this->result);
    }
    public function getjobcount(){
        $json_obj = $this->readJson();
        
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';

        
        if( !$userId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
        } 
        

        $this->result = $this->Login_model-> getjobcount($userId);
        $this->jsonOutput($this->result);
    }
    public function  listJobItems(){
        $json_obj = $this->readJson();
        
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
        $jobTableId = isset($json_obj->jobTableId) ? trim($json_obj->jobTableId): '';

        
        if( !$userId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
        } 
        if( !$jobTableId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'jobTableId is blank']);     
        } 
        

        $this->result = $this->Login_model->  listJobItems($userId,$jobTableId);
        $this->jsonOutput($this->result);
    }
    
    public function listJobDetails(){
        $json_obj = $this->readJson();
        
        //$userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
        $orderId = isset($json_obj->orderId) ? trim($json_obj->orderId): '';

        
        //if( !$userId ){
			//$this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
       // } 
        if( !$orderId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'orderId is blank']);     
        } 
        

        $this->result = $this->Login_model->  listJobDetails($orderId);
        $this->jsonOutput($this->result);
    
    }

    

    public function test(){
        $json_obj = $this->readJson();
        
        $orderId = isset($json_obj->orderId) ? trim($json_obj->orderId): '';
        

        
        if( !$orderId ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'orderId is blank']);     
        } 
       
        

        $this->result = $this->Login_model->  test($orderId);
        $this->jsonOutput($this->result);
    
    }
    public function  createGalleryFolder(){
        $json_obj = $this->readJson();
        
        $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
        
        $folderName = isset($json_obj->folderName) ? trim($json_obj->folderName): '';
        
        if( !$userId ){
            $this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     

        } 
        if( !$folderName ){
			$this->jsonOutput(['status'=> 'fail', 'message' => 'folderName is blank']);     
        } 
        $this->result = $this->Login_model-> createGalleryFolder($userId,$folderName);
        $this->jsonOutput($this->result);
    }

    

    public function  listGalleryFolder(){
    $json_obj = $this->readJson();
    $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
   if( !$userId )
{
    $this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']); 
} 
  
    $this->result = $this->Login_model->  listGalleryFolder($userId);
    $this->jsonOutput($this->result);
    
    }

public function  createGalleryDetails(){
    
    $json_obj = $this->readJson();
    
    $galleryTableId = isset($json_obj->galleryTableId) ? trim($json_obj->galleryTableId): '';
    
    $imgUrl = isset($json_obj->imgUrl) ? trim($json_obj->imgUrl): '';
    
    if( !$galleryTableId ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'galleryTableId is blank']);     
        
    } 
    if( !$imgUrl ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'imgUrl is blank']);     
    } 
  
    $this->result = $this->Login_model-> createGalleryDetails($galleryTableId,$imgUrl);
    $this->jsonOutput($this->result);

}


public function  uploadImage(){
    $this->load->library('s3_upload');
    $json_obj = $this->readJson();
    
    $galleryTableId = isset($json_obj->galleryTableId) ? trim($json_obj->galleryTableId): '';
    $userId = isset($json_obj->userId) ? trim($json_obj->userId): '';
   
    $productImage = isset($json_obj->productImage) ? trim($json_obj->productImage): '';
    
    if( !$galleryTableId ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'galleryTableId is blank']);     
        
    } 
    if( !$productImage ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'productImage is blank']);     
    } 
    if( !$userId ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'userId is blank']);     
    } 
  

    $this->result = $this->Login_model-> uploadImage($galleryTableId,$productImage,$userId);
    $this->jsonOutput($this->result);

}
public function  listGalleryDetails(){
    $json_obj = $this->readJson();
    $galleryTableId = isset($json_obj->galleryTableId) ? trim($json_obj->galleryTableId): '';
  
    if( !$galleryTableId ){
        $this->jsonOutput(['status'=> 'fail', 'message' => 'galleryTableId is blank']);     
    } 

   
    $this->result = $this->Login_model->  listGalleryDetails($galleryTableId);
    $this->jsonOutput($this->result);

}



    

   

    

}



