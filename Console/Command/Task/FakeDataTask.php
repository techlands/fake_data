<?php
class FakeDataTask extends Shell
{

	public function gibberishWords($number = 1, $glue = ' ')
	{
		static $words = array(
			'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'curabitur', 
			'vel', 'hendrerit', 'libero', 'eleifend', 'blandit', 'nunc', 'ornare', 'odio', 'ut', 'orci', 
			'gravida', 'imperdiet', 'nullam', 'purus', 'lacinia', 'a', 'pretium', 'quis', 'congue', 
			'praesent', 'sagittis', 'laoreet', 'auctor', 'mauris', 'non', 'velit', 'eros', 'dictum', 
			'proin', 'accumsan', 'sapien', 'nec', 'massa', 'volutpat', 'venenatis', 'sed', 'eu', 
			'molestie', 'lacus', 'quisque', 'porttitor', 'ligula', 'dui', 'mollis', 'tempus', 'at', 
			'magna', 'vestibulum', 'turpis', 'ac', 'diam', 'tincidunt', 'id', 'condimentum', 'enim', 
			'sodales', 'in', 'hac', 'habitasse', 'platea', 'dictumst', 'aenean', 'neque', 'fusce', 'augue', 
			'leo', 'eget', 'semper', 'mattis', 'tortor', 'scelerisque', 'nulla', 'interdum', 'tellus', 
			'malesuada', 'rhoncus', 'porta', 'sem', 'aliquet', 'et', 'nam', 'suspendisse', 'potenti', 
			'vivamus', 'luctus', 'fringilla', 'erat', 'donec', 'justo', 'vehicula', 'ultricies', 'varius', 
			'ante', 'primis', 'faucibus', 'ultrices', 'posuere', 'cubilia', 'curae', 'etiam', 'cursus', 
			'aliquam', 'quam', 'dapibus', 'nisl', 'feugiat', 'egestas', 'class', 'aptent', 'taciti', 'sociosqu', 
			'ad', 'litora', 'torquent', 'per', 'conubia', 'nostra', 'inceptos', 'himenaeos', 'phasellus', 'nibh', 
			'pulvinar', 'vitae', 'urna', 'iaculis', 'lobortis', 'nisi', 'viverra', 'arcu', 'morbi', 'pellentesque', 
			'metus', 'commodo', 'ut', 'facilisis', 'felis', 'tristique', 'ullamcorper', 'placerat', 'aenean', 
			'convallis', 'sollicitudin', 'integer', 'rutrum', 'duis', 'est', 'etiam', 'bibendum', 'donec', 
			'pharetra', 'vulputate', 'maecenas', 'mi', 'fermentum', 'consequat', 'suscipit', 'aliquam', 'habitant', 
			'senectus', 'netus', 'fames', 'quisque', 'euismod', 'curabitur', 'lectus', 'elementum', 'tempor', 
			'risus', 'cras', 
		);
		
		$out = array();
		
		do
		{
			$pick = $words[array_rand($words)];
			
			if (in_array($pick, $out))
			{
				continue;
			}
			else
			{
				$out[] = $pick;
			}
			
			if (count($out) == $number)
			{
				break;
			}
		}
		while (1==1);
		
		if ($glue === false)
		{
			return $out;
		}
		else
		{
			return implode(' ', $out);
		}
	}
	
	public function gibberishSentance($minWords = 8, $maxWords = 16, $ucFirst = true, $period = true)
	{
		$sentance = $this->gibberishWords(rand($minWords, $maxWords));
		
		if ($ucFirst)
		{
			$sentance = ucfirst($sentance);
		}
		
		if ($period)
		{
			$sentance = $sentance . '.';
		}
		
		return $sentance;
	}
	
	public function gibberishParagraph($minSentances = 3, $maxSentances = 6, $minWords = 8, $maxWords = 16)
	{
		$number = rand($minSentances, $maxSentances);
		$sentances = array();
		
		for ($i = 0; $i < $number; $i++)
		{
			$sentances[] = $this->gibberishSentance($minWords, $maxWords);
		}
		
		return implode(' ', $sentances);
	}

	public function gibberishContent($paragraphs = 1, $html = false, $separator = "\n\n", $minSentances = 3, $maxSentances = 6, $minWords = 8, $maxWords = 16)
	{
		$out = array();
		
		for ($i = 0; $i < $paragraphs; $i++)
		{
			$paragraph = $this->gibberishParagraph($minSentances, $maxSentances, $minWords, $maxWords);
			
			if ($html)
			{
				$out[] = '<p>' . htmlspecialchars($paragraph) . '</p>';
			}
			else
			{
				$out[] = $paragraph;
			}
		}
		
		return implode($separator, $out);
	}

	public function phone()
	{
		return sprintf(
			'%s%s%s-%s%s%s-%s%s%s%s',
			rand(2,9), rand(0,8), rand(0,9),
			rand(2,9), rand(0,9), rand(0,9),
			rand(0,9), rand(0,9), rand(0,9), rand(0,9)
		);
	}

	public function associatedFields($modelName, $fieldName = 'id', $conditions = array(), $number = 1, $tryLimit = 100)
	{
		static $cache = array();
		
		$Model = ClassRegistry::init($modelName);
		
		if (!empty($conditions))
		{
			$data = $Model->find('list', array('fields' => array("{$modelName}.{$fieldName}", "{$modelName}.{$fieldName}"), 'conditions' => $conditions));
		}
		else
		{
			if (empty($cache[$modelName]))
			{
				$cache[$modelName] = $Model->find('list', array('fields' => array("{$modelName}.{$fieldName}", "{$modelName}.{$fieldName}")));
			}
			
			$data = $cache[$modelName];
		}
		
		$dataLength = count($data);
		
		if (is_array($number))
		{
			if (count($number) < 2)
			{
				$number[1] = $number[0];
			}
			
			$maxNumber = max($number);
			$minNumber = min($number);
		}
		else
		{
			$maxNumber = $minNumber = $number;
		}
		
		
		if ($maxNumber > $dataLength)
		{
			$maxNumber = $dataLength;
		}
		
		if ($minNumber > $dataLength)
		{
			$minNumber = $dataLength;
		}
		
		
		$number = rand($minNumber, $maxNumber);
		$out = array();
		
		$i = 0;
		do
		{
			$i++;
			$pick = array_rand($data);
			
			if (in_array($pick, $out))
			{
				continue;
			}
			else
			{
				$out[] = $pick;
			}
			
			if (count($out) == $number)
			{
				break;
			}
		}
		while ($i < $tryLimit);
		
		return $out;
	}
	
	public function firstName()
	{
		static $firstNames = array(
			'Aiden', 'Jackson', 'Mason', 'Liam', 'Jacob', 'Jayden', 'Ethan', 'Noah', 
			'Lucas', 'Logan', 'Caleb', 'Caden', 'Jack', 'Ryan', 'Connor', 'Michael', 
			'Elijah', 'Brayden', 'Benjamin', 'Nicholas', 'Alexander', 'William', 'Matthew', 
			'James', 'Landon', 'Nathan', 'Dylan', 'Evan', 'Luke', 'Andrew', 'Gabriel', 
			'Gavin', 'Joshua', 'Owen', 'Daniel', 'Carter', 'Tyler', 'Cameron', 'Christian', 
			'Wyatt', 'Henry', 'Eli', 'Joseph', 'Max', 'Isaac', 'Samuel', 'Anthony', 'Grayson', 
			'Zachary', 'David', 'Christopher', 'John', 'Isaiah', 'Levi', 'Jonathan', 
			'Oliver', 'Chase', 'Cooper', 'Tristan', 'Colton', 'Austin', 'Colin', 'Charlie', 
			'Dominic', 'Parker', 'Hunter', 'Thomas', 'Alex', 'Ian', 'Jordan', 'Cole', 'Julian', 
			'Aaron', 'Carson', 'Miles', 'Blake', 'Brody', 'Adam', 'Sebastian', 'Adrian', 
			'Nolan', 'Sean', 'Riley', 'Bentley', 'Xavier', 'Hayden', 'Jeremiah', 'Jason', 
			'Jake', 'Asher', 'Micah', 'Jace', 'Brandon', 'Josiah', 'Hudson', 'Nathaniel', 'Bryson', 
			'Ryder', 'Justin', 'Bryce', 'Sophia', 'Emma', 'Isabella', 'Olivia', 'Ava', 
			'Lily', 'Chloe', 'Madison', 'Emily', 'Abigail', 'Addison', 'Mia', 'Madelyn', 
			'Ella', 'Hailey', 'Kaylee', 'Avery', 'Kaitlyn', 'Riley', 'Aubrey', 'Brooklyn', 
			'Peyton', 'Layla', 'Hannah', 'Charlotte', 'Bella', 'Natalie', 'Sarah', 'Grace', 
			'Amelia', 'Kylie', 'Arianna', 'Anna', 'Elizabeth', 'Sophie', 'Claire', 'Lila', 'Aaliyah', 
			'Gabriella', 'Elise', 'Lillian', 'Samantha', 'Makayla', 'Audrey', 'Alyssa', 'Ellie', 
			'Alexis', 'Isabelle', 'Savannah', 'Evelyn', 'Leah', 'Keira', 'Allison', 'Maya', 'Lucy', 
			'Sydney', 'Taylor', 'Molly', 'Lauren', 'Harper', 'Scarlett', 'Brianna', 'Victoria', 
			'Liliana', 'Aria', 'Kayla', 'Annabelle', 'Gianna', 'Kennedy', 'Stella', 'Reagan', 
			'Julia', 'Bailey', 'Alexandra', 'Jordyn', 'Nora', 'Caroline', 'Mackenzie', 'Jasmine', 
			'Jocelyn', 'Kendall', 'Morgan', 'Nevaeh', 'Maria', 'Eva', 'Juliana', 'Abby', 'Alexa', 
			'Summer', 'Brooke', 'Penelope', 'Violet', 'Kate', 'Hadley', 'Ashlyn', 'Sadie', 'Paige', 
			'Katherine', 'Sienna', 'Piper',
		);
		
		return $firstNames[array_rand($firstNames)];
	}
	
	public function lastName()
	{
		static $lastNames = array(
			'Adams', 'Alexander', 'Allen', 'Anderson', 'Bailey', 'Baker', 
			'Barnes', 'Bell', 'Bennett', 'Brooks', 'Brown', 'Bryant', 'Butler', 'Campbell', 'Carter', 
			'Clark', 'Coleman', 'Collins', 'Cook', 'Cooper', 'Cox', 'Davis', 'Diaz', 'Edwards', 'Evans', 
			'Flores', 'Foster', 'Garcia', 'Gonzales', 'Gonzalez', 'Gray', 'Green', 'Griffin', 'Hall', 
			'Harris', 'Hayes', 'Henderson', 'Hernandez', 'Hill', 'Howard', 'Hughes', 'Jackson', 'James', 
			'Jenkins', 'Johnson', 'Jones', 'Kelly', 'King', 'Lee', 'Lewis', 'Long', 'Lopez', 'Martin', 
			'Martinez', 'Miller', 'Mitchell', 'Moore', 'Morgan', 'Morris', 'Murphy', 'Nelson', 'Parker', 
			'Patterson', 'Perez', 'Perry', 'Peterson', 'Phillips', 'Powell', 'Price', 'Ramirez', 'Reed', 
			'Richardson', 'Rivera', 'Roberts', 'Robinson', 'Rodriguez', 'Rogers', 'Ross', 'Russell', 
			'Sanchez', 'Sanders', 'Scott', 'Simmons', 'Stewart', 'Taylor', 'Thomas', 'Thompson', 'Torres', 
			'Turner', 'Walker', 'Ward', 'Washington', 'Watson', 'White', 'Williams', 'Wilson', 'Wood', 
			'Wright', 'Young',
		);
		
		return $lastNames[array_rand($lastNames)];
	}
	
	public function email($username, $domain, $addRandom)
	{
		$username = $this->username($username, $addRandom);
		
		return strtolower(sprintf('%s@%s', $username, $domain));
	}
	
	public function username($parts, $addRandom)
	{
		if (!is_array($parts))
		{
			$parts = array($parts);
		}
		
		$rand = '';
		if ($addRandom)
		{
			$rand = rand(100,999);
		}
		
		return strtolower(implode('', $parts) . $rand);
	}

	public function postal()
	{
		$postal = '';
		for ($i = 0; $i < 5; $i++)
		{
			$postal .= rand(0, 9);
		}
		return $postal;
	}
	
	public function streetAddressPrimary($useName, $numberLength, $useDirection, $abbreviateDirection, $abbreviateType)
	{
		static $names1 = array(
			'Amber','Blue','Bright','Broad','Burning','Cinder','Clear','Colonial','Cotton', 
			'Cozy','Crystal','Dewy','Dusty','Easy','Emerald','Fallen','Foggy','Gentle','Golden', 
			'Grand','Green','Harvest','Hazy','Heather','Hidden','High','Honey','Indian','Iron', 
			'Jagged','Lazy','Little','Lost','Merry','Middle','Misty','Noble','Old','Pleasant','Quaking', 
			'Quiet','Red','Rocky','Round','Rustic','Shady','Silent','Silver','Sleepy','Stony','Sunny', 
			'Tawny','Thunder','Umber','Velvet','Wishing',
		);
		
		static $names2 = array(
			'Anchor','Apple','Autumn','Barn','Beacon','Bear','Berry','Blossom','Bluff', 
			'Branch','Brook','Butterfly','Cider','Cloud','Creek','Dale','Deer','Elk','Embers', 
			'Fawn','Forest','Fox','Gate','Goose','Grove','Hickory','Hills','Horse','Island', 
			'Lagoon','Lake','Leaf','Log','Mountain','Nectar','Oak','Panda','Pine','Pioneer', 
			'Pond','Pony','Prairie','Quail','Rabbit','Rise','River','Robin','Shadow','Sky', 
			'Spring','Timber','Treasure','View','Wagon','Willow','Zephyr',
		);
		
		static $typesFull = array(
			'Avenue','Boulevard','Road','Street','Drive','Manor','Place','Circle','Square', 'Loop', 
		);
		
		static $typesAbbreviated = array(
			'Ave','Blvd','Rd','St','Dr','Mnr','Pl','Cir','Sq',
		);
		
		static $directionsFull = array(
			'North', 'South', 'East', 'West',
		);
		
		static $directionsAbbreviated = array(
			'N', 'S', 'E', 'W', 'NE', 'NW', 'SE', 'SW',
		);
		
		$address = array();
		
		//Generate Bldg Number
		$address['number'] = '';
		for ($i = 0; $i < $numberLength; $i++)
		{
			if ($i == 0) 
			{
				$address['number'] .= rand(1, 9);
			}
			else
			{
				$address['number'] .= rand(0, 9);
			}
		}
		
		//Generate Direction
		if ($useDirection)
		{
			if ($abbreviateDirection)
			{
				$address['direction'] = $directionsAbbreviated[array_rand($directionsAbbreviated)];
			}
			else
			{
				$address['direction'] = $directionsFull[array_rand($directionsFull)];
			}
			
			$directionAfter = (bool) rand(0,1);
		}
		
		//Generate Name
		if ($useName)
		{
			$nameLength = rand(1, 2);
			if ($nameLength == 1)
			{
				$address['name'] = $names2[array_rand($names2)];
			}
			else
			{
				$address['name'] = $names1[array_rand($names1)] . ' ' . $names2[array_rand($names2)];
			}
		}
		else
		{
			$address['name'] = rand(1,199);
			$address['name'] .= $this->ordinal($address['name']);
		}
		
		//Generate Type
		if ($abbreviateType)
		{
			$address['type'] = $typesAbbreviated[array_rand($typesAbbreviated)];
		}
		else
		{
			$address['type'] = $typesFull[array_rand($typesFull)];
		}
		
		//Move Direction After
		if (!empty($directionAfter))
		{
			$direction = $address['direction'];
			unset($address['direction']);
			$address['direction'] = $direction;
		}
		
		return implode(' ', $address);
	}
	
	public function streetAddressSecondary($numberLength = 3, $useHash = true)
	{
		static $types = array(
			'','Unit','Apt','Suite','Ste','Bay'
		);

		$address = $types[array_rand($types)];
		
		if (strlen($address)) $address .= ' ';
		
		if ($useHash || !strlen($address)) $address .= '#';
		
		for ($i = 0; $i < $numberLength; $i++)
		{
			if ($i == 0) 
			{
				$address .= rand(1, 9);
			}
			else
			{
				$address .= rand(0, 9);
			}
		}
		
		return $address;
	}

	public function companyName($length = 1)
	{
		static $types = array(
			' Corp.',', Inc.',', LLC',' LTD' 
		);
		
		return ucwords($this->gibberishWords($length)) . $types[array_rand($types)];
		
	}
	
	public function website($length = 1, $hyphenate = false, $www = true, $trailingSlash = true)
	{
		static $tlds = array(
			'com', 'net', 'org', 'cc', 'info', 'biz', 'us'
		);
		
		$words = explode(' ', $this->gibberishWords($length));
		$address = 'http://';
		
		if ($www)
		{
			$address .= 'www.';
		}
		
		$address .= implode(($hyphenate ? '-' : ''), $words);
		
		$address .= '.' . $tlds[array_rand($tlds)];
		
		if ($trailingSlash)
		{
			$address .= '/';
		}
		
		return $address;
	}
	
	public function genericName($length = 1, $ucAll = true)
	{
		$name = $this->gibberishWords($length);
		if ($ucAll)
		{
			return ucwords($name);
		}
		
		return $name;
	}

	public function timestamp($from, $to = null, $format = 'sql')
	{
		if (!is_numeric($from))
		{
			$from = strtotime($from);
		}
		
		if (empty($to))
		{
			$to = time();
		}
		elseif (!is_numeric($to))
		{
			$to = strtotime($to);
		}
		
		switch ($format)
		{
			case 'sql':
				$format = 'Y-m-d H:i:s';
				break;
		}
		
		return date($format, rand($from, $to));
	}

	public function ordinal($number)
	{
		// Special case "teenth"
		if ( ($number / 10) % 10 != 1 )
		{
			// Handle 1st, 2nd, 3rd
			switch( $number % 10 )
			{
				case 1: return 'st';
				case 2: return 'nd';
				case 3: return 'rd'; 
			}
		}
		// Everything else is "nth"
		return 'th';
	}
	
	public function alphaNumeric($length = 1, $randCase = true)
	{
		$out = '';
		$data = str_shuffle('abcdefghijklmnopqrstuvwxyz1234567890');
		$dataLength = strlen($data);
		
		for ($i = 0; $i < $length; $i++)
		{
			$pos = mt_rand(0, ($dataLength - 1));
			
			if ($randCase && mt_rand(0,1))
			{
				$out .= strtoupper($data{$pos});
			}
			else
			{
				$out .= $data{$pos};
			}
		}
		
		return $out;
	}
}