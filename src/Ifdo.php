<?php
namespace Biigle\IfdoParser;

class Ifdo
{
	private $jsonStr = "";
	private $jsonArr = [];
	private $validator;
	private $debug = false;

	const AVAILABLE_VERSIONS = ['v2.0.0', 'v2.0.1', 'v2.1.0'];

	public static function fromFile($path): Ifdo
	{
		$data = file_get_contents($path);

		return Ifdo::fromString($data);
	}

	public static function fromString($data): Ifdo
	{
		return new Ifdo($data, true);
	}

	public function __construct($json)
	{
		$this->jsonStr = $json;
		$this->jsonArr = json_decode($json, true);
	}

	public function getValidator()
	{
		if ( ! $this->validator)
		{
			$this->revalidate();
		}
		return $this->validator;
	}

	public function revalidate()
	{
		$this->validator = new \JsonSchema\Validator;
		$version         = $this->getIfdoVersion();
		$decoded         = json_decode($this->jsonStr);
		$this->validator->validate($decoded, (object) ['$ref' => 'file://' . realpath("assets/ifdo-$version.json")]);

		if ($this->getDebug() && ! empty($this->getErrors()))
		{
			$this->printErrors();
		}
	}

	public function isValid(): bool
	{
		return $this->getValidator()->isValid();
	}

	public function getErrors(): Array {
		return $this->getValidator()->getErrors();
	}

	public function setDebug($value): void
	{
		$this->debug = true;
	}

	public function getDebug(): bool
	{
		return $this->debug;
	}

	public function getJsonData()
	{
		return $this->jsonArr;
	}

	public function getImageSetHeader()
	{
		return $this->getJsonData()['image-set-header'];
	}

	public function getImageSetItems()
	{
		return $this->getJsonData()['image-set-items'];
	}

	public function getIfdoVersion(): string
	{
		$dataVersion = $this->getImageSetHeader()['image-set-ifdo-version'];
		if ( ! in_array($dataVersion, self::AVAILABLE_VERSIONS))
		{
			throw new \Exception("Unsupported iFDO version `$dataVersion`");
		}
		return $dataVersion;
	}

	public function printErrors(): void
	{
		print "\nJSON does not validate. Violations:\n";
		foreach ($this->getValidator()->getErrors() as $error)
		{
			printf("[%s] %s\n", $error['property'], $error['message']);
		}
	}
}
