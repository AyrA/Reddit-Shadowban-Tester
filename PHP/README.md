# PHP Checker

Shadowban checker implementation in PHP.

## How to use

Place `CA.pem` and `tester.php` into the same folder, then include `tester.php` in your script.

```PHP
$result=RedditTester::checkUser('username_here','token_here',$errno,$errstr);
if($errno===0){
	$result=json_decode($result,TRUE);
	//result now has the keys: name,code,desc,remain,sign
}
else{
	//Do something with errno and errstr here
	if($errno===400){
		//Token used up
	}
	elseif($errno===404){
		//Invalid token
	}
	else{
		//Other API errors
	}
}
```

For a description of the keys on success,
check the [API documentation](https://cable.ayra.ch/reddit/api.php).

## Tips

- Store the token in a database or separate file so you can easily change it when it expires. Make sure the file is not accessible to the general visitor.
- Store a successful result for a week so you don't consume additional requests for when you check the same user again.
