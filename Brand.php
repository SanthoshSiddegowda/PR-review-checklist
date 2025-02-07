<?php
class Brand extends AppModel {
	var $name = 'Brand';
	public $useTable = 'brands';
	var $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'company_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	function getBrands() {
		$brands = array();
		$brands = $this->find('list', array('fields'=>array('Brand.id','Brand.name'),'recursive'=>-1));
		return $brands;
	}

	function getAllBrands( $status = 1 )
	{
		$brands = array();
		$brands = $this->find( 'all', array(
			'conditions' => array( 'Brand.status' => $status ),
			'fields' => array( 'Brand.id', 'Brand.name', 'Brand.imageurl', 'Brand.isvisible', 'Brand.pictureurl', 'Brand.sequence' ),
			'recursive' => -1,
		) );
		return $brands;
	}

	function getBrandSkus( $brandIds = array() ){
		$skunits = array();
		if( !empty( $brandIds )){
			$skunits = $this->find( 'all', array(
				'conditions' => array( 'Brand.status' => 1, 'Brand.id' => $brandIds, 'Skunit.active' => 1 ),
				'fields'	 => array( 'Brand.id', 'Skunit.id', 'Skunit.name', 'Skunit.skucode' ),
				'recursive'	 => -1,
				'joins'		 => array(
					array( 'table' => 'skunits', 'alias' => 'Skunit', 'type' => 'LEFT', 'conditions' => 'Skunit.brand_id=Brand.id' ),
				),
			));
		}
		return $skunits;
	}

	function getSkuBrands ( $skuIds = array() ){
		$skunits = array();
		if( !empty( $skuIds )){
			$skunits = $this->find( 'list', array(
				'conditions' => array(  'Skunit.id' => $skuIds ),
				'fields'	 => array( 'Skunit.id' , 'Brand.name' ),
				'recursive'	 => -1,
				'joins'		 => array(
					array( 'table' => 'skunits', 'alias' => 'Skunit', 'type' => 'LEFT', 'conditions' => 'Skunit.brand_id=Brand.id' ),
				),
			));
		}
		return $skunits;
	}

	function findByIDs( $brandIds, $queryFields = array( 'Brand.*' ), $isVisible = array( 0, 1 ) ) {
		$condition[ 'Brand.isvisible' ] = $isVisible;
		$condition[ 'Brand.id' ] = $brandIds;
		$brandData = $this->find( 'all', array(
			'conditions' => $condition,
			'fields' => $queryFields,
			'recursive' => -1
		) );
		if ( !empty( $brandData ) ) {
			$brandData = Set::combine( $brandData, '{n}.Brand.id', '{n}.Brand' );
		}
		return $brandData;
	}

	function findByERPIDs( $brandErpIds, $queryFields = array( 'Brand.*' ), $isVisible = array( 0, 1 ) ) {
		$condition[ 'Brand.isvisible' ] = $isVisible;
		$condition[ 'Brand.erp_id' ] = $brandErpIds;
		$brandData = $this->find( 'all', array(
			'conditions' => $condition,
			'fields' => $queryFields,
			'recursive' => -1
		) );
		if ( !empty( $brandData ) ) {
			$brandData = Set::combine( $brandData, '{n}.Brand.erp_id', '{n}.Brand' );
		}
		return $brandData;
	}
}
?>