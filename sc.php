 <?php 
require 'config.php';
require 'database_connection.php';
    $conn = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
    $link = $conn->connect();
// $sql = "SELECT * FROM `customer`,address GROUP by email order by customer_id";
$sql = "SELECT  c.*,a.* FROM customer c LEFT JOIN `address` a on a.address_id = c.address_id WHERE c.customer_group_id<>10 and c.email <> '' GROUP by c.email";
// SELECT DISTINCT email FROM `order` WHERE email NOT IN (SELECT DISTINCT email from customer) and customer_group_id <> 9


// create a file pointer connected to the output stream
$output = fopen('customers.csv', 'w');

// output the column headings
fputcsv($output, array('First Name', 'Last Name', 'Email','Company','Address1','Address2','City','Province','Province Code','Country','Country Code','Zip','Phone','Accepts Marketing','Total Spent','Total Orders','Tags','Note','Tax Exempt'));



// loop over the rows, outputting them
// while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);
$result = $link->query($sql);
if($result->num_rows >0)
{
	while($row = $result->fetch_assoc()) {
		
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
		array_push($customer, '');
		array_push($customer, '');
		array_push($customer, '');
		array_push($customer, '');	
		array_push($customer, '');
		array_push($customer, '');	
		fputcsv($output, $customer);
		echo "<pre>";
		print_r($row);
		// exit;
	}
	fclose($output);
}

