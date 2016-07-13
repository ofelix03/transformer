# Transformer
A simple associative (i.e. a key:value pair) data transformer which transforms the keys of an array data to some  other specified keys. It also supports casting of data values to a specified type (e.g integer, boolean, string, \DateTime e.t.c)


## What This Package Seeks To Achieve

1. Streamlining the process of transforming data keys. 
2. Reducing cluttering of application controller  and business logic with data normalization activities such as transformation of data keys.
3. Upholding the [DRY](http://www.wikipedia/dry_principle "DRY") principle by employing classes for different data keys transformation.



## Code to support the above claims

Let first start with a code snippet that tries to present what we might normally do without this package.

```php
	// An http request payload from a POST request
	$data = array(
			'title' => 'Hey, am a title',
			'description' => 'Hey, am simple description',
			'pub_date' => '2016-05-10 10:05:30',
			'comments_count' => '10',
		);

	// In some projects, where you have received an http request 
	// payload like the above, which needs to be normalized to 
	// match some specific database field names, the snippet below
	// is a representation of what we might do without this package
	// (but with this package could be done much more fluently without    
	// cluttering our application controller or codebase);

	if (isset($data['title'])) {
		$data['newTitle'] = $data['title']
		unset($dat['title']);
	}

	if (isset($data['description'])) {
		$data['text'] = $data['description'];
		unset($data['description']);
	}

	if (isset($data['pub_date'])) {
		$data['published_date'] = $data['pub_date'];
		unset($data['pub_date']);
	}

	if (isset($data['comments_count'])) {
		// Here, we do just type casting, from string to integer
		$data['comments_count'] = (int) $data['comments_count']
	}

	// Now $data contains the transformed keys with their associated data

```

Now let try to use **Transformer** package to streamline and remove the clutter in the above code snippet, even keeping our code [DRY](http://www.wikipedia/dry_principle "DRY") in the process.

```php
	// Using the same payload($data) as in the above snippet.

	// Here, we're using comoposer, hence we'll pull in composer's `vendor/autoload.php` to do it magic (autoloading)
	require 'vendor/autoload.php';

	// Also to use this library, we'll need the `Transformer` class
	use Ofelix03\Transformer\Transformer

	// We first create a class PostTransformer that tailors our transformation 
	// to our business model.

	// PostTransformer is suppose to implement just 2 methods 
	// 1. createRequestKeys
	// 2. createMorphKeys
	// Both methods returns an array of key definitions which represent the definitions of 'requestKeys' and 'morphKeys' as we will see in the code snippet below.

	class PostTransformer extends Transformer {
 
		// The return array contains the keys expected from the request 
		// payload (.i.e $data)
		public function createRequestKeys() {
			return array(
					'title',
					'description',
					'pub_date',
					'comments_count',
				);
		}

		public function createMorphKeys() { 
			return array(
				'newTitle',
				'text',
				'published_date',  
				// This will cast the type of the value to a an integer 
				'comments_count:int' 
				);
		}
	}

	// Time to instantiate our new PostTransformer class, with the http request payload ($data)
	// we want to transform it keys, and hopefully do some casting on some values that requires type casting.
	$postTransformer = new PostTransformer($data);

	// Now we transform the keys, and perform any necessary casting in the process by invoking transform() on 
	// $postTransformer like so:
	$result = $postTransformer->transform();
	
	var_dump($result);

	// This should be the output of the var_dump method
	array (5) {
		["title"] => string(15) "Some Post title"
		["description"] => string(36) "Some post description here and there"
		["published_status"] => bool(true)
		["published_date"] => string(19) "20-06-2016 12:30:30"
		["comments_count"] => int(0)
	}

```



## Installation
1. **Using composer**

	``` composer require ofelix03\transformer ```

	__NB__: Make sure to ``` require vendor\autoload.php ``` at the top of the the file you want to use the transformer package in.

2. **Use github clone**
	You can also clone the github repository for this package

	Simply follow the laid out steps below. Make sure you already have git environment set up on your machine. You can checkout how to do so on Git's [official site](http://www.git-scm.com "Git official site") 

	+ **Step 1**

		Open your terminal and run the `git clone` command below:
		```php
			git clone https:\\www.github.com\ofelix03\transformer.git
		```

	+ **Step 2**

		Copy the php files inside ```src``` directory to any location in your app directory structure and require them in this order:

		```php
			require 'app\root\folder\transformer\TypeCaster.php';
			require 'app\root\folder\transformer\KeysBag.php';
			require 'app\root\folder\transformer\Transformer.php';
		```


## Usage

```php
	
	// Assume $data is the request payload that requires some of it's keys 
	// to be morphed into other keys

	$data = array(
		'title' => 'Some Post Title',
		'description' => 'Some post description here and there',
		'pub_status' => '1',
		'pub_dat' => '20-06-2016 12:30:30',
		'comments_count => '10'
	);

	// Here, $reqKeys list the keys in $data which are going to 
	// be transformed to some other keys

	$reqKeys = array(
		'description',
		'pub_status',
		'pub_date',
		'comments_count'
	);

	// $morpKeys holds a sequential listing of keys which 
	// are to replace the keys speicified in the $reqKeys. 
	// The listing should parrallel to that found in $reqKeys.

	// **NB**: Any key specified in $reqKeys that has no matching 
	// index position in the $morpKeys array is skipped

	$morphKeys = array(
		'text',
		'published_status:bool',
		'published_date',
		'comments_cout:int'
	);

	// Time to transform some keys using the `\ofelix03\Transformer\Transformer` class 
	// **NB**: Make sure to autoload the class before using it, else it would not work. 

	$transformer = new \Ofelix03\Transformer\Transformer($data, $reqKeys, $morphKeys);

	$result = $transformer->transform();
	
	var_dump($result);

	// $result should now hold your transformed keys with their corresponding values
	// This is the result of var_dump($result)
	array (5) {
		["title"] => string(15) "Some Post title"
		["description"] => string(36) "Some post description here and there"
		["published_status"] => bool(true)
		["published_date"] => string(19) "20-06-2016 12:30:30"
		["comments_count"] => int(0)
	}

```

## Other API's on \Ofelix03\Transformer\Tranformer 
* **Tranformer::isStrict(): bool**

	This checks whether the transformation should  be done in strict mode or not. Strict mode, first checks if the `$reqKeys` is equal in length to the `$morphKeys` and throws an exception if they are not. Returns boolean (TRUE|FALSE).

* **Transformer::setStrict($mode = false)**

	This allows you to set the mode for the transformation. The default mode is `FALSE` if no argument is passed.

* **Transformer::isTransformed(): bool**

	Checks whether the data (or payload) has already been transformed, this help save time, not transforming data that has already been transformed but instead getting the transformed data with `Transformer::getMorphedData()`.

* **Transformer::setRequestKeys(array $reqKeys = [])** 

	This method allows you to set the $request keys after you've already created an instance of  `Ofelix03\Transformer\Transformer` class.
	**NB**: If this method is to be called, it should be called before calling `Transformer::transform()` else a run-time exception is thrown.

* **Transformer::setMorphKeys(array $morphKeys = [])**

	This method allows you to set the keys that are to replace the $request keys during the transformation

* **Transformer::setRequestPayload(array $data)** 

	This is used to set the data that needs to be transformed. This can be used to override the request data set during the construction of the transformer object. 
	**NB**: Call this method before invoking `Tranformer::transform()`

* **Transformer::transform($reqPayload = [], $strictMode = false): array** 

	This is the method that does the magic -- transforming keys to other speicified keys. And also perform type casting if necessary.

	+ *$reqPayload*
		This argument is optional. It's the data upon which the transformation is applied on, using the $requestKeys and $morphKeys definitions

	+ *$strictMode*
		This second argument indicates the mode used for the transformation. It's optional. Remember this can also be set with the `Transformer::setStrict()` as discussed earlier.

* **Transformer::getMorphedData(): array**

	This method is called after invoking ```Transformer::tranform()``` to get the transformed data (data with it's keys morphed into other keys).


## Casting

The following are the types currently supported for casting data.

1. Integer (int)
2. String (string)
3. Array (array)
4. Boolean (bool)

## Feature Features

PHP's [DateTime](http://php.net/manual/en/class.datetime.php "PHP DateTime Class") class and [Carbon](http://carbon.nesbot.com/ 'PHP Carbon Library') will be supported in the  next release. Other primitive types such as [double](http://php.net/manual/en/language.types.float.php "Dobule")(also known as float) and [object](http://php.net/manual/en/language.types.object.php "Object") will be supported with future releases.


