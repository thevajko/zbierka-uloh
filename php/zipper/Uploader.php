<?php


class Uploader
{
    const UPLOAD_DIR = "uploads";
    /**
     * @var String
     */
    private $uploadsDir;
    private $uniqueId;

    public function __construct(string $uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
        if (is_file($this->uploadsDir)) {
            unlink($this->uploadsDir);
        }
        if (!is_dir($this->uploadsDir)) {
            mkdir($this->uploadsDir, 0700);
        }
        $this->generateUniqueId();
    }

    public function saveUploadedFile(): string
    {
        try {
            switch ($_FILES['userfile']['error']) {
                case UPLOAD_ERR_OK:
                    if (!move_uploaded_file($_FILES['userfile']['tmp_name'],
                        $this->getFullFileNameWithDir(basename($_FILES['userfile']['name'])))) {
                        throw new RuntimeException('Súbor nebol poslaný správnym spôsobom.');
                    }
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('Žiadny súbor nebol poslaný.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Veľkosť súboru presahuje povolený limit.');
                default:
                    throw new RuntimeException('Neznáma chyba.');
            }
            return '';
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }
    }

    public function getFilesList(): array
    {
        $fileList = array_diff(scandir($this->uploadsDir), ['..', '.']);
        $fileList = array_filter($fileList, fn($item) => substr($item, 0, 40) == $this->uniqueId);
        return array_map(fn($item) => substr($item, 41), $fileList);
    }

    public function zipAndDownload(): void
    {
        $zip = new ZipArchive();
        $tmpFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . tmpfile();
        $zip->open($tmpFile, ZipArchive::CREATE);
        foreach ($this->getFilesList() as $fileName) {
            if (file_exists($this->getFullFileNameWithDir($fileName))) {
                $zip->addFile($this->getFullFileNameWithDir($fileName), $fileName);
            }
        }
        $zip->close();
        $this->sendZipFile($tmpFile);
    }

    private function sendZipFile($tmpFile): void
    {
        foreach ($this->getFilesList() as $fileName) {
            unlink($this->getFullFileNameWithDir($fileName));
        }

        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="download.zip"');
        echo file_get_contents($tmpFile);
        unlink($tmpFile);
    }

    private function getFullFileNameWithDir($fileName): string
    {
        return $this->uploadsDir . DIRECTORY_SEPARATOR . $this->uniqueId . '-' . $fileName;
    }

    private function generateUniqueId(): void
    {
        if (isset($_COOKIE['uniqueId'])) {
            $this->uniqueId = $_COOKIE['uniqueId'];
        } else {
            $this->uniqueId = bin2hex(random_bytes(20));
            setcookie('uniqueId', $this->uniqueId);
        }
    }
}