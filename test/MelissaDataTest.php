<?php
	include('../MelissaData.php');
	include('ID.php');

	class MelissaDataTest extends PHPUnit_Framework_TestCase
	{
		public function testClassAndMethodNames()
		{
			$this->assertTrue(class_exists('MelissaData'));

			$MelissaDataReflection = new ReflectionClass("MelissaData");

			$this->assertTrue($MelissaDataReflection->hasProperty("ID"));
			$this->assertTrue($MelissaDataReflection->hasProperty("URL"));

			$this->assertTrue($MelissaDataReflection->hasMethod('__construct'));
			$this->assertTrue($MelissaDataReflection->hasMethod('setID'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getID'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getURL'));
			$this->assertTrue($MelissaDataReflection->hasMethod('setURL'));
			$this->assertTrue($MelissaDataReflection->hasMethod('sendCommand'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getZipCodeCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getZipCodeBuyList'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getCityCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getCityBuyList'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getCountyCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getCountyBuyList'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getStateCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getStateBuyList'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getRadiusCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getRadiusBuyList'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getStreetRecordCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getStreetRecordBuyList'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getCitiesByCountyCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getCitiesByState'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getCountiesByState'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getStatesCount'));
			$this->assertTrue($MelissaDataReflection->hasMethod('getZipsByCity'));
		}

		public function testSendCommand()
		{
			$MelissaData = new MelissaData(ID::get());

			$options = new \stdClass;
			$options->st = 'wa';

			$xml = $MelissaData->sendCommand('get/CountiesByState', $options);

			$this->assertSelectRegExp('StatusCode', '/Approved/', TRUE, $xml);
		}

		public function testGetZipCodeCount()
		{
			$command = 'get/zip';

			$MelissaData = new MelissaData(ID::get());

			$options = new \stdClass;
			$options->zip = '98119';

			$returnXML = $MelissaData->sendCommand($command, $options);

			$doc = DOMDocument::loadXML($returnXML);

			// Strip all data out of the returned XML to only hold the XML structure
			$XML = preg_replace('/<Zip>(.+)<\/Zip>/', '<Zip></Zip>', $doc->saveXML());
			$XML = preg_replace('/<ContactPersonInfo>(.+)<\/ContactPersonInfo>/', '<ContactPersonInfo></ContactPersonInfo>', $XML);
		 	$XML = preg_replace('/<Details>(.+)<\/Details>/', '<Details></Details>', $XML);
		  	$XML = preg_replace('/<IncludeAll>[a-zA-Z]+<\/IncludeAll>/', '<IncludeAll/>', $XML);
		  	$XML = preg_replace('/<AppendToFile>[a-zA-Z]+<\/AppendToFile>/', '<AppendToFile/>', $XML);
		  	preg_match_all('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9\-]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', $XML, $out);
		  	$Street = preg_replace('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9\-]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', '<Street><StartNumber/><EndNumber/><Geography></Geography><Zip/><Count></Count></Street>', $out[0][0]);
		  	$XML = preg_replace('/<CountDetails><StreetRange>(.+)<\/StreetRange><\/CountDetails>/', $Street, $XML);
		  	$XML = preg_replace('/<Count>[0-9]+<\/Count>/', '<Count></Count>', $XML);
		  	$XML = preg_replace('/<StatusCode>[a-zA-Z]+<\/StatusCode>/', '<StatusCode></StatusCode>', $XML);

		  	$this->assertXmlStringEqualsXmlFile('XML/getZipCodeCount.xml', $XML);
		}

		public function testGetZipCodeBuyList()
		{
			$command = 'buy/zip';

			$MelissaData = new MelissaData(ID::get());

			$options = new \stdClass;
			$options->zip = '98119';

			$returnXML = $MelissaData->sendCommand($command, $options);

			$doc = DOMDocument::loadXML($returnXML);

			$XML = preg_replace('/<Zip>(.+)<\/Zip>/', '<Zip></Zip>', $doc->saveXML());
			$XML = preg_replace('/<ContactPersonInfo>(.+)<\/ContactPersonInfo>/', '<ContactPersonInfo></ContactPersonInfo>', $XML);
		 	$XML = preg_replace('/<Details>(.+)<\/Details>/', '<Details></Details>', $XML);
		  	$XML = preg_replace('/<IncludeAll>[a-zA-Z]+<\/IncludeAll>/', '<IncludeAll/>', $XML);
		  	$XML = preg_replace('/<AppendToFile>[a-zA-Z]+<\/AppendToFile>/', '<AppendToFile/>', $XML);
		  	preg_match_all('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9\-]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', $XML, $out);
		  	$Street = preg_replace('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9\-]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', '<Street><StartNumber/><EndNumber/><Geography></Geography><Zip/><Count></Count></Street>', $out[0][0]);
		  	$XML = preg_replace('/<CountDetails><StreetRange>(.+)<\/StreetRange><\/CountDetails>/', $Street, $XML);
		  	$XML = preg_replace('/<Count>[0-9]+<\/Count>/', '<Count></Count>', $XML);
		  	$XML = preg_replace('/<StatusCode>[a-zA-Z]+<\/StatusCode>/', '<StatusCode></StatusCode>', $XML);
		  	$XML = preg_replace('/<Id>[0-9]+<\/Id>/', '<Id></Id>', $XML);
		  	$XML = preg_replace('/<Usage>[0-9]+<\/Usage>/', '<Usage></Usage>', $XML);
		  	$XML = preg_replace('/<DownloadURL>.+<\/DownloadURL>/', '<Usage></Usage>', $XML);
		  	$XML = preg_replace('/<DeliveredQty>[0-9]+<\/DeliveredQty>/', '<DeliveredQty></DeliveredQty>', $XML);

		  	$this->assertXmlStringEqualsXmlFile('XML/getZipCodeBuyList.xml', $XML);
		}

		public function testGetCityCount()
		{
			$command = 'get/city';

			$MelissaData = new MelissaData(ID::get());

			$options = new \stdClass;
			$options->city = 'wa;seattle';

			$returnXML = $MelissaData->sendCommand($command, $options);

			$doc = DOMDocument::loadXML($returnXML);

			$XML = preg_replace('/<Zip>(.+)<\/Zip>/', '<Zip></Zip>', $doc->saveXML());
			$XML = preg_replace('/<ContactPersonInfo>(.+)<\/ContactPersonInfo>/', '<ContactPersonInfo></ContactPersonInfo>', $XML);
		 	$XML = preg_replace('/<Details>(.+)<\/Details>/', '<Details></Details>', $XML);
		  	$XML = preg_replace('/<IncludeAll>[a-zA-Z]+<\/IncludeAll>/', '<IncludeAll/>', $XML);
		  	$XML = preg_replace('/<AppendToFile>[a-zA-Z]+<\/AppendToFile>/', '<AppendToFile/>', $XML);
		  	preg_match_all('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9a-zA-Z\,\-\ ]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', $XML, $out);
		  	$Street = preg_replace('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9a-zA-Z\,\-\ ]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', '<Street><StartNumber/><EndNumber/><Geography></Geography><Zip/><Count></Count></Street>', $out[0][0]);
		  	$XML = preg_replace('/<CountDetails><StreetRange>(.+)<\/StreetRange><\/CountDetails>/', $Street, $XML);
		  	$XML = preg_replace('/<Count>[0-9]+<\/Count>/', '<Count></Count>', $XML);
		  	$XML = preg_replace('/<StatusCode>[a-zA-Z]+<\/StatusCode>/', '<StatusCode></StatusCode>', $XML);

		  	$this->assertXmlStringEqualsXmlFile('XML/getCityCount.xml', $XML);
		}

		public function testGetCityBuyList()
		{
			$command = 'buy/city';

			$MelissaData = new MelissaData(ID::get());

			$options = new \stdClass;
			$options->city = 'wa;seattle';

			$returnXML = $MelissaData->sendCommand($command, $options);

			$doc = DOMDocument::loadXML($returnXML);

			$XML = $doc->saveXML();
			$XML = preg_replace('/<Zip>(.+)<\/Zip>/', '<Zip></Zip>', $XML);
			$XML = preg_replace('/<ContactPersonInfo>(.+)<\/ContactPersonInfo>/', '<ContactPersonInfo></ContactPersonInfo>', $XML);
		 	$XML = preg_replace('/<Details>(.+)<\/Details>/', '<Details></Details>', $XML);
		  	$XML = preg_replace('/<IncludeAll>[a-zA-Z]+<\/IncludeAll>/', '<IncludeAll/>', $XML);
		  	$XML = preg_replace('/<AppendToFile>[a-zA-Z]+<\/AppendToFile>/', '<AppendToFile/>', $XML);
			preg_match_all('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9a-zA-Z\,\-\ ]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', $XML, $out);
			$Street = preg_replace('/<Street><StartNumber\/><EndNumber\/><Geography>[0-9a-zA-Z\,\-\ ]+<\/Geography><Zip\/><Count>[0-9]+<\/Count><\/Street>/', '<Street><StartNumber/><EndNumber/><Geography></Geography><Zip/><Count></Count></Street>', $out[0][0]);
		  	$XML = preg_replace('/<CountDetails><StreetRange>(.+)<\/StreetRange><\/CountDetails>/', $Street, $XML);
		  	$XML = preg_replace('/<Count>[0-9]+<\/Count>/', '<Count></Count>', $XML);
		  	$XML = preg_replace('/<StatusCode>[a-zA-Z]+<\/StatusCode>/', '<StatusCode></StatusCode>', $XML);
		  	$XML = preg_replace('/<Id>[0-9]+<\/Id>/', '<Id></Id>', $XML);
		  	$XML = preg_replace('/<Usage>[0-9]+<\/Usage>/', '<Usage></Usage>', $XML);
		  	$XML = preg_replace('/<DownloadURL>.+<\/DownloadURL>/', '<Usage></Usage>', $XML);
		  	$XML = preg_replace('/<DeliveredQty>[0-9]+<\/DeliveredQty>/', '<DeliveredQty></DeliveredQty>', $XML);

		  	$this->assertXmlStringEqualsXmlFile('XML/getCityBuyList.xml', $XML);
		}
	}
?>