# Transformer
A simple associative (i.e. a key:value pair) data transformer which transforms the key's of an array data to some  other specified keys. It also supports casting of data values to a specified PHP data type.

## What problems this package tries to solve

1. Streamlining the process of transforming data keys 
2. Reducing cluttering of application controller with data normalization activities such as keys transformation
3. Upholding the [DRY](http://www.wikipedia/dry_principle "DRY") principle by employing classes for different data set key transformation.



## Code to support the above claims

Let first start with a code snippet that tries to present what we might normally do without this package.

```php
	// An http request payload data from a POST request
	$data = array(
			'title' => 'Hey, am a title',
			'description' => 'Hey, am simple description',
			'pub_date' => '2016-05-10 10:05:30',
			'comments_count' => '10',
		);


	// In some project, where you have recieved an http request 
	// payload like the above, which needs to be normalized to 
	// match some specific database field names. 

	// The snippet below is a representation of what we might do without this package
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
		// Here, we want to do both key transformation 
		// from 'pub_date' to a much concise, unambigious and readable 
		// 'published_date' and also cast  the type of the 
		// published date to a PHP \DateTime object;
		$data['published_date'] = \DateTime($data['pub_date']);
		unset($data['pub_date']);
	}

	if (isset($data['comments_count'])) {
		// Here, we did just type casting, from string to integer
		$data['comments_count'] = (int)$data['comments_count']
	}

```

Now let try to use this pacakge to streamline and remove the clutter in the above code snippet, even keeping our code DRY, not copying and pasting implementation all over the place.

```php
	// Using the same $data as in the above snippet.

	// We first create a class PostTransformer that tailors our transformation 
	// to our business model.


	// PostTransformer is suppose to implement just 2 methods 
	// 1. createRequestKeys
	// 2. createMorphKeys
	// Both methods returns an array of key definitions which represent the definitions of 'requestKeys' and 'morphKeys' as defined at the [bottom](http://ofelix3) of this page

	class PostTransformer extends Transformer {
 
		// The return array contains the keys expected from the request 
		// payload data (.i.e $data)
		public function createRequestKeys() {
			return array(
					'title',
					'description',
					'pub_date',
					'comments_count',
				);
		}

		public function createMorphKeys(){ 
			return array(
				'newTitle',
				'text',
				// This will transform the 'bub_date' key of the request 
				// payload data to 'published_date' and also cast the type
				// of its value for that key to a PHP \DateTime
				'published_date:datetime',  

				// This will cast the type of the value to a an integer 
				'comments_count:int' 
				);
		}
	}

	// Time to instantiate our new PostTransformer class

	$postTransformer = new PostTransformer($data);

	$result = $postTransformer->transform();
	
	// $result now contains our transformed keys with their corresponding values.

```



## Installation
1. **Using composer**

	``` composer require ofelix03\transformer ```

	__NB__: Make sure to ``` require vendor\autoload.php ``` at the top of the the file you want to use the transformer package in.

2. **Use github clone**
	You can also clone the github repository for this package

	Simply run the following commands in your terminal. Make sure you already have git environment set up on your machine.

	+ **Step 1**

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
	
	// Assume $data is the request data we expect to change, 
	// some of it keys to some other keys

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

	// NB: Any key specified in $reqKeys that has no matching 
	// index position in the $morpKeys array is skipped

	$morphKeys = array(
		'text',
		'published_status:bool',
		'published_date:dateTime',
		'comments_cout:int'
	);

	// Time to transform some keys using the \ofelix03\Transformer\Transformer class 
	// NB: Make sure to autoload the class before using it, else it would not work. 

	$transformer = new \Ofelix03\Transformer\Transformer($data, $reqKeys, $morphKeys);

	$result = $transformer->transform();
	
	// $result should hold your transformed keys with their corresponding data

```

## Other API's on \Ofelix03\Transformer\Tranformer 
* **Tranformer::isStrict(): bool**

	This checks whether the tranformation should  be done in strict mode or not. Strict mode, first checks if the $reqKeys is equal in length to the $morphKeys and throws an exception if they are not. Returns boolean (TRUE|FALSE).

* **Transformer::setStrict($mode = false)**

	This allows you to set the mode for the transformation. The default mode is ```FALSE```, if no argument is passed.

* **Transformer::isTransformed(): bool**

	Checks whether  the data has already been transformed, this help save wasting time, transforming data that has already been transformed.

* **Transformer::setRequestKeys(array $reqKeys = [])** 

	This method allows you to set the $request keys after you've already created an instance of  ```Ofelix03\Transformer\Transformer``` class.
	**NB**: If this method is to be called, it should be called before calling ```Transformer::transform()```, else a run-time exception is thrown.

* **Transformer::setMorphKeys(array $morphKeys = [])**

	This method allows ou to set the keys that are to replace the $request keys during the transformation

* **Transformer::setRequestPayload(array $data)** 

	This is used to set the data that needs to be transformed. This can be used to override, the request data set during the construction of the transformer object. 
	NB: Call this method before invoking `Tranformer::transform()`

* **Transformer::transform($reqPayload = [], $strictMode = false): array** 

	This is the method that does the magic, transforming keys to other speicified keys. And also type casting, if specified.

	+ *$reqPayload*
		This argument is optional. This is the data upon which the transformation is applied on, using the $requestKeys and $morphKeys definitions

	+ *$strictMode*
		This second argument indicates the mode used for the transformation. It's optional.

* **Transformer::getMorphedData(): array**

	This method is called after invoking ```Transformer::tranform()``` to get the transformed data (data with keys morphed into other keys)


## Casting

The following are the types currently supported for casting data.
1. Integer (int)
2. String (string)
3. Array (array)
4. Boolean (bool)
5. Float (float)

PHP's DateTime and Carbon will be supported in the  next release.


