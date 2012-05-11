<? $trans = array_pop($this->transactions);	?>
<script type="text/javascript">
try {

	transNum = "<?=$trans['raw']['donation']['transaction_id'];?>";
	amount = "<?=$trans['amount'];?>".replace(/\$/gi,"").replace(/,/gi,"");
	dfid = "<?=$trans['form_id'];?>";
	dlevel = "<?=$trans['level_id'];?>";
	
	var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-11074517-5']);
	  _gaq.push(['_addTrans',
		transNum, // Order ID
		"", // Affiliation
		amount, // Total
		"", // Tax
		"", // Shipping
		"", // City
		"", // State
		"" // Country
	]);
	 _gaq.push(['_addItem',
	transNum, // Order ID
	dfid+"-"+dlevel, // SKU
	"Donation "+dfid+"-"+dlevel, // Product Name 
	dfid, // Category
	amount, // Price
	"1" // Quantity
	]);
	_gaq.push(['_trackTrans']);
			
} catch(err) {}
</script>