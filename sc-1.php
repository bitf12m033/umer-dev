 <?php 
require 'config.php';
require 'database_connection.php';

function validate_phone_number($phone)
{
     // Allow +, - and . in phone number
     $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
     // Remove "-" from number
     $phone_to_check = str_replace("-", "", $filtered_phone_number);
     // Check the lenght of number
     // This can be customized if you want phone number from a specific country
     if (strlen($phone_to_check) < 8 || strlen($phone_to_check) > 14) {
        return false;
     } else {
       return true;
     }
}
    $conn = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
    $link = $conn->connect();
// $sql = "SELECT * FROM `customer`,address GROUP by email order by customer_id";
$sql = "SELECT  c.*,a.* FROM customer c LEFT JOIN `address` a on a.address_id = c.address_id WHERE c.customer_group_id<>10 and c.email <> '' GROUP by c.email";
// SELECT DISTINCT email FROM `order` WHERE email NOT IN (SELECT DISTINCT email from customer) and customer_group_id <> 9


// create a file pointer connected to the output stream
$output = fopen('customers-2.csv', 'w');

// output the column headings
fputcsv($output, array('First Name', 'Last Name', 'Email','Company','Address1','Address2','City','Province','Province Code','Country','Country Code','Zip','Phone','Accepts Marketing','Total Spent','Total Orders','Tags','Note','Tax Exempt'));



// loop over the rows, outputting them
// while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);
$result = $link->query($sql);
if($result->num_rows >0)
{
	while($row = $result->fetch_assoc()) {
		echo "<pre>";
		$row['telephone'] = str_replace('L','0',$row['telephone']);
		// if(preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $row['telephone'])) {
		if (validate_phone_number($row['telephone']) == true) {
		  echo "Phone:: ".$row['telephone']." is Valid <br/>";
		}
		else
		{
		  echo "Phone==".$row['telephone']." is InValid <br/>";
		  $row['telephone'] = '';
		}
		print_r($row);
		// exit;
		$customer = array();
		array_push($customer, $row['firstname']);
		array_push($customer, $row['lastname']);
		array_push($customer, $row['email']);
		array_push($customer, $row['company']);
		array_push($customer, $row['address_1']);
		array_push($customer, $row['address_2']);
		array_push($customer, $row['city']);
		array_push($customer, '');
		array_push($customer, '');
		array_push($customer, 'India');
		array_push($customer, 'IN');
		array_push($customer, $row['postcode']);
		array_push($customer, $row['telephone']);
		array_push($customer, 'yes');
		array_push($customer, '');
		array_push($customer, '');
		array_push($customer, '');	
		array_push($customer, '');
		array_push($customer, '');	
		fputcsv($output, $customer);

	}
}


// $sql = "SELECT c.*,o.* FROM customer c Left JOIN `order` o on o.customer_id = c.customer_id where o.customer_id = 0 and c.email is NOT null and o.email is not null GROUP BY o.email";
$sql = 'SELECT * FROM `order` WHERE email NOT IN (SELECT DISTINCT email from customer) and (customer_group_id <> 9 or customer_group_id <> 10) and email <>""';

$result = $link->query($sql);
if($result->num_rows >0)
{
	while($row = $result->fetch_assoc()) {
		
		echo "<pre>";
		$row['telephone'] = str_replace('L','0',$row['telephone']);
		if (validate_phone_number($row['telephone']) == true) {
		  echo "Phone:: ".$row['telephone']." is Valid <br/>";
		}
		else
		{
		  echo "Phone==".$row['telephone']." is InValid <br/>";
		  $row['telephone'] = '';
		}
		print_r($row);
		// exit;
		$customer = array();
		array_push($customer, $row['firstname']);
		array_push($customer, $row['lastname']);
		array_push($customer, $row['email']);
		array_push($customer, '');
		array_push($customer, $row['shipping_address_1']);
		array_push($customer, $row['shipping_address_2']);
		array_push($customer, $row['shipping_city']);
		array_push($customer, '');
		array_push($customer, '');
		array_push($customer, 'India');
		array_push($customer, 'IN');
		array_push($customer, $row['shipping_postcode']);
		array_push($customer, $row['telephone']);
		array_push($customer, 'yes');
		array_push($customer, '');
		array_push($customer, '');
		array_push($customer, '');	
		array_push($customer, '');
		array_push($customer, '');	
		fputcsv($output, $customer);
		
	}
}
	fclose($output);