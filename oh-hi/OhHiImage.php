<?php
	/*
	
		This file is part of OhHi
		http://github.com/brandon-lockaby/OhHi
		
		(c) Brandon Lockaby http://about.me/brandonlockaby for http://oh-hi.info
		
		OhHi is free software licensed under Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0)
		http://creativecommons.org/licenses/by-nc-sa/3.0/

	*/
	/*
		BETLOG 2012-07-05--20.06.00
		altered populateExif() to produce more refined output
	*/
	
	class OhHiImage {
		public $dir;
		public $filename;
		public $exif;
		public $width;
		public $height;
		
		protected function __construct($dir, $filename) {
			$this->dir = $dir;
			$this->filename = $filename;
			$this->populateExif();
 			$this->populateDimensions();
		}
		
		private function populateExif() {
			$this->exif = array();
			$file = SITE_ROOT . '/' . $this->dir . '/' . $this->filename;
			$exif_IFD0 = exif_read_data($file, 'IFD0' ,0);
			$exif_EXIF = exif_read_data($file, 'EXIF' ,0);
			$exif_COMPUTED = exif_read_data($file, 'COMPUTED' ,0);      			
			if($exif_EXIF === false || $exif_IFD0 === false || $exif_COMPUTED === false) {
				return;
			}
 			if (@array_key_exists('FileName', $exif_IFD0))
 			  $this->exif['FileName'] = $exif_IFD0['FileName'];
			
			if (@array_key_exists('DateTimeOriginal', $exif_IFD0))
			  $this->exif['DateTimeOriginal'] = $exif_IFD0['DateTimeOriginal'];

			if (@array_key_exists('FocalLength', $exif_EXIF)) {
// 			  list($d1,$d2)=split('/',$exif_EXIF['FocalLength']);
                list($d1,$d2)=explode('/',$exif_EXIF['FocalLength']);
			  if ($d2 >= 1)
			    $this->exif['FocalLength'] = $d1/$d2 . 'mm';
			}
			
			if (@array_key_exists('ApertureFNumber', $exif_COMPUTED))
			  $aperture = $exif_COMPUTED['ApertureFNumber'];
			else {
			  $aperture = $exif_EXIF['FNumber'];
// 			  list($d1,$d2)=split('/',$exif_EXIF['FNumber']);
                list($d1,$d2)=explode('/',$exif_EXIF['FNumber']);
			    $aperture = 'f/' . $d1/$d2;
			}
			$this->exif['Aperture'] = $aperture;
			  
            if (@array_key_exists('ExposureTime', $exif_EXIF)) {
                $exposuretime = $exif_EXIF['ExposureTime'];
                if ($exposuretime >= 1)
                    $this->exif['ExposureTime'] = $exposuretime . 's';
                else {
                    list($d1,$d2)=split('/',$exposuretime);
                    if ($d1 == 1 && $d2 > 1)
                        $this->exif['ExposureTime'] = $exposuretime . 's';
                    else {
                        $this->exif['ExposureTime'] = $d1/$d2 . 's';
                    }
                }
            }
            else {
                trigger_error($exif_IFD0['FileName']);
//FIX ME                
//GET DIVIDE BY ZERO AND UNUSUAL OUTPUT ERRORS ON MEDIATEK CAMERA IMAGES THAT HAVE UNUSUAL SHUTTERSPEED/EXPOSURETIME FORMAT
//LIKE IMG_20150301_142906-y600px.jpg
//AND IMG_20150304_160805-y600px.jpg
            }

			if (@array_key_exists('ExposureBiasValue', $exif_EXIF)) {
// 			  list($d1,$d2)=split('/',$exif_EXIF['ExposureBiasValue']);
			  list($d1,$d2)=explode('/',$exif_EXIF['ExposureBiasValue']);
			  if ($d1 != 0)
			    $this->exif['ExposureBiasValue'] = 'EV:' . $d1/$d2;
			  else
			   $this->exif['ExposureBiasValue'] = 'EV:' . $d1;
			}
			  
			if (@array_key_exists('ISOSpeedRatings', $exif_EXIF))
			  $this->exif['ISOSpeedRatings'] = 'ISO:' . $exif_EXIF['ISOSpeedRatings'];
			  
            if (@array_key_exists('Flash', $exif_EXIF)) {
                $fdata = $exif_EXIF['Flash'];
                if ($fdata == 0) $fdata = 'No Flash';
                else if ($fdata == 1) $fdata = 'Flash';
                else if ($fdata == 5) $fdata = 'Flash, strobe return light not detected';
                else if ($fdata == 7) $fdata = 'Flash, strobe return light detected';
                else if ($fdata == 8) $fdata = 'Flash, Compulsory, did not fire';
                else if ($fdata == 9) $fdata = 'Flash, Compulsory';
                else if ($fdata == 13) $fdata = 'Flash, Compulsory, Return light not detected';
                else if ($fdata == 15) $fdata = 'Flash, Compulsory, Return light detected';
                else if ($fdata == 16) $fdata = 'No Flash';
                else if ($fdata == 24) $fdata = 'No Flash';
                else if ($fdata == 25) $fdata = 'Flash, Auto-Mode';
                else if ($fdata == 29) $fdata = 'Flash, Auto-Mode, Return light not detected';
                else if ($fdata == 31) $fdata = 'Flash, Auto-Mode, Return light detected';
                else if ($fdata == 32) $fdata = 'No Flash';
                else if ($fdata == 65) $fdata = 'Flash, Red Eye';
                else if ($fdata == 69) $fdata = 'Flash, Red Eye, Return light not detected';
                else if ($fdata == 71) $fdata = 'Flash, Red Eye, Return light detected';
                else if ($fdata == 73) $fdata = 'Flash, Red Eye, Compulsory';
                else if ($fdata == 77) $fdata = 'Flash, Red Eye, Compulsory, Return light not detected';
                else if ($fdata == 79) $fdata = 'Flash, Red Eye, Compulsory, Return light detected';
                else if ($fdata == 89) $fdata = 'Flash, Red Eye, Auto-Mode';
                else if ($fdata == 93) $fdata = 'Flash, Red Eye, Auto-Mode, Return light not detected';
                else if ($fdata == 95) $fdata = 'Flash, Red Eye, Auto-Mode, Return light detected';
                else $fdata = 'Unknown: ' . $fdata;
//                 $return['flashdata'] = $fdata;
               $this->exif['Flash'] = $fdata;
            }              

/*
0x0 = No Flash
0x1 = Fired
0x5 = Fired, Return not detected
0x7 = Fired, Return detected
0x8 = On, Did not fire
0x9 = On, Fired
0xd = On, Return not detected
0xf = On, Return detected
0x10    = Off, Did not fire
0x14    = Off, Did not fire, Return not detected
0x18    = Auto, Did not fire
0x19    = Auto, Fired
0x1d    = Auto, Fired, Return not detected
0x1f    = Auto, Fired, Return detected
0x20    = No flash function
0x30    = Off, No flash function
0x41    = Fired, Red-eye reduction
0x45    = Fired, Red-eye reduction, Return not detected
0x47    = Fired, Red-eye reduction, Return detected
0x49    = On, Red-eye reduction
0x4d    = On, Red-eye reduction, Return not detected
0x4f    = On, Red-eye reduction, Return detected
0x50    = Off, Red-eye reduction
0x58    = Auto, Did not fire, Red-eye reduction
0x59    = Auto, Fired, Red-eye reduction
0x5d    = Auto, Fired, Red-eye reduction, Return not detected
0x5f    = Auto, Fired, Red-eye reduction, Return detected
*/            
            
//             if (@array_key_exists('LensID',$exif_EXIF)) {
//                 $this->exif['LensID'] = $exif_EXIF['LensID'];
//             }
            if (@array_key_exists('Model', $exif_EXIF))
              $this->exif['Model'] = $exif_EXIF['Model'];

            if (@array_key_exists('Software', $exif_EXIF))
              $this->exif['Software'] = $exif_EXIF['Software'];
              
              
		}
		
		private function populateDimensions() {
			list($this->width, $this->height) = getimagesize(SITE_ROOT . '/' . $this->dir . '/' . $this->filename);
		}
		
		public static function fromFile($dir, $filename) {
			if(!preg_match('/(\.jpg$)|(\.jpeg$)/', $filename)) {
				return NULL;
			}
			if(!file_exists(SITE_ROOT . '/' . $dir . '/' . $filename)) {
				return NULL;
			}
			return new OhHiImage($dir, $filename);
		}
		
        public function getHtml() {
            $html_safe_dir = html_safe($this->dir);
            $html_safe_filename = html_safe($this->filename);
            $html_safe_url = html_safe($this->dir . ($this->dir == '\\' || $this->dir == '/' ? '' : '/') . $this->filename);
            $html_safe_exif = html_safe($this->exif);
            $html_safe_width = html_safe($this->width);
            $html_safe_height = html_safe($this->height);
            $result = "<span class=\"image\" data-dir=\"{$html_safe_dir}\" data-filename=\"{$html_safe_filename}\">" .
                "<img src=\"{$html_safe_url}\" width=\"{$html_safe_width}\" height=\"{$html_safe_height}\"/>" .
                "<div class=\"more\">" .
                "<div class=\"exif\">";
            foreach($html_safe_exif as $key => $value) {
                    $result .= "{$value}<br>";
            }
            $result .= "</div>"; // exif            
            $result .= "</div>"; // more
            $result .= "</span>"; // class="image"
            return $result;
        }
    }
?>