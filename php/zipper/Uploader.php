<?php


class Uploader
{
    const UPLOAD_DIR = "uploads";
    /**
     * @var String
     */
    private $uploadsDir;
    private $filesList = [];
    private $uniqueId = "";

    public function __construct(string $uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
        if (is_file($this->uploadsDir)) {
            unlink($this->uploadsDir);
        }
        if (!is_dir($this->uploadsDir)) {
            mkdir($this->uploadsDir, 0700);
        }
        $this->setUniqueId();
        $this->updateFilesList();
    }

    function saveUploadedFile(): string
    {
        try {
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                        move_uploaded_file($_FILES['file']['tmp_name'], $this->getFullFileNameWithDir(basename($_FILES['file']['name'])));
                    } else {
                        throw new RuntimeException('Súbor nebol poslaný správnym spôsobom.');
                    }
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('Žiadny súbor nebol poslaný.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Veľkosť súbor presahuje povolený limit.');
                default:
                    throw new RuntimeException('Neznáma chyba.');
            }
            $this->updateFilesList();
            return '';
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }

    }

    function getFilesList(): array
    {
        return $this->filesList;
    }

    /**
     * Zip all files in directory and
     */
    function zipAndDownload()
    {
        $zip = new ZipArchive();
        $tmpFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . tmpfile();
        $zip->open($tmpFile, ZipArchive::CREATE);
        foreach ($this->filesList as $fileName) {
            if (file_exists($this->getFullFileNameWithDir($fileName))) {
                $zip->addFile($this->getFullFileNameWithDir($fileName), $fileName);
            }
        }
        $zip->close();

        $this->sendFile($tmpFile);

    }

    function sendFile($tmpFile) {
        foreach ($this->filesList as $fileName) {
            unlink($this->getFullFileNameWithDir($fileName));
        }

        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="download.zip"');
        echo file_get_contents($tmpFile);
        unlink($tmpFile);
    }

    /**
     * @param array $filesList
     */
    public function updateFilesList(): void
    {
        $this->filesList = array_diff(scandir($this->uploadsDir), array('..', '.'));
        $this->filesList = array_filter($this->filesList, fn($item) => substr($item, 0,40) == $this->getUniqueId());
        $this->filesList = array_map(fn($item) => substr($item, 41), $this->filesList);
    }

    private function getFullFileNameWithDir($fileName) : string {
        return $this->uploadsDir . DIRECTORY_SEPARATOR .$this->getUniqueId() . '-' . $fileName;
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }

    /**
     * @param string $uniqueId
     */
    public function setUniqueId(): void
    {
        if (isset($_COOKIE['uniqueId'])) {
         $this->uniqueId = $_COOKIE['uniqueId'];
    } else {
         $this->uniqueId = bin2hex(random_bytes(20));
         setcookie('uniqueId', $this->uniqueId, ['samesite' => 'None']);
        }
    }

}