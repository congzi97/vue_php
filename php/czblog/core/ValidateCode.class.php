<?php

class ValidateCode {
	private $code;//验证码
    private $codelen = 4; // 验证码长度
	private $width   = 100;//宽度
	private $height  = 30;//高度
	private $img;//图形资源句柄
	private $font;//指定的字体
	private $fontsize = 8;//指定字体大小
	private $fontcolor;//指定字体颜色
	//构造方法初始化
	public function __construct( ? array  $data) {
		$this->font     = PUBLIC_DIR.'/static/font/kaiti.ttf';
        $this->codelen  = $data['length'];
        $this->width    = $data['size']['width'];
        $this->height   = $data['size']['height'];
        $this->code     = $_SESSION['verifyCode'];
	}
    //获取随机码 、 储存验证码
	private function createCode() {
        return $this->code;
	}
	//生成背景
	private function createBg() {
		$this->img = imagecreatetruecolor($this->width, $this->height);
		$color     = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
		imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
	}
	//生成文字
	private function createFont() {
		$_x = $this->width/$this->codelen;
		for ($i = 0; $i < $this->codelen; $i++) {
			$this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
			// imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x*$i+mt_rand(1, 5), $this->height/1.4, $this->fontcolor, $this->font, $this->code[$i]);
			imagestring($this->img, 5, $_x*$i+mt_rand(1, 5), $this->height/5, $this->code[$i], $this->fontcolor);
		}
	}
	//生成线条、雪花
	private function createLine() {
		//线条
		for ($i = 0; $i < 2; $i++) {
			$color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
			imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
		}
		//雪花
		for ($i = 0; $i < 100; $i++) {
			$color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
			imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
		}
	}
	//输出
	private function outPut() {
		header('Content-type:image/png');
		imagepng($this->img);
		imagedestroy($this->img);
	}
	//对外生成
	public function doimg() {
		$this->createBg();
		$this->createCode();
		$this->createLine();
		$this->createFont();
		$this->outPut();
	}
	################################动态 GIF 验证码###########################
	function GIF_Image_Code() {
		$authstr      = $this->createCode();
		$board_width  = $this->width;
		$board_height = $this->height;
		// 生成一个32帧的GIF动画
		for ($i = 0; $i < 32; $i++) {
			ob_start();
			$image = imagecreate($board_width, $board_height);
			imagecolorallocate($image, 0, 0, 0);
			// 设定文字颜色数组
			$colorList[] = ImageColorAllocate($image, 15, 73, 210);
			$colorList[] = ImageColorAllocate($image, 0, 64, 0);
			$colorList[] = ImageColorAllocate($image, 0, 0, 64);
			$colorList[] = ImageColorAllocate($image, 0, 128, 128);
			$colorList[] = ImageColorAllocate($image, 27, 52, 47);
			$colorList[] = ImageColorAllocate($image, 51, 0, 102);
			$colorList[] = ImageColorAllocate($image, 0, 0, 145);
			$colorList[] = ImageColorAllocate($image, 0, 0, 113);
			$colorList[] = ImageColorAllocate($image, 0, 51, 51);
			$colorList[] = ImageColorAllocate($image, 158, 180, 35);
			$colorList[] = ImageColorAllocate($image, 59, 59, 59);
			$colorList[] = ImageColorAllocate($image, 0, 0, 0);
			$colorList[] = ImageColorAllocate($image, 1, 128, 180);
			$colorList[] = ImageColorAllocate($image, 0, 153, 51);
			$colorList[] = ImageColorAllocate($image, 60, 131, 1);
			$colorList[] = ImageColorAllocate($image, 0, 0, 0);
			$fontcolor   = ImageColorAllocate($image, 0, 0, 0);
			$gray        = ImageColorAllocate($image, 245, 245, 245);
			$color       = imagecolorallocate($image, 255, 255, 255);
			$color2      = imagecolorallocate($image, 255, 0, 0);
			imagefill($image, 0, 0, $gray);
			$space = 25;// 字符间距
			if ($i > 0)// 屏蔽第一帧
			{
				for ($k = 0; $k < strlen($authstr); $k++) {
					$colorRandom = mt_rand(0, sizeof($colorList)-1);
					$float_top   = rand(0, 4);
					$float_left  = rand(0, 3);
					imagestring($image, 6, $space*$k+5, $top+$float_top*5, substr($authstr, $k, 1), $colorList[$colorRandom]);
				}
			}

			for ($k = 0; $k < 20; $k++) {
				$colorRandom = mt_rand(0, sizeof($colorList)-1);
				imagesetpixel($image, rand()%70, rand()%15, $colorList[$colorRandom]);
			}
			// 添加干扰线
			for ($k = 0; $k < 3; $k++) {
				$colorRandom = mt_rand(0, sizeof($colorList)-1);
				// $todrawline = rand(0,1);
				$todrawline = 1;
				if ($todrawline) {
					imageline($image, mt_rand(0, $board_width), mt_rand(0, $board_height), mt_rand(0, $board_width), mt_rand(0, $board_height), $colorList[$colorRandom]);
				} else {
					$w = mt_rand(0, $board_width);
					$h = mt_rand(0, $board_width);
					imagearc($image, $board_width-floor($w/2), floor($h/2), $w, $h, rand(90, 180), rand(180, 270), $colorList[$colorRandom]);
				}
			}
			imagegif($image);
			imagedestroy($image);
			$imagedata[] = ob_get_contents();
			ob_clean();
			++$i;
		}
		$gif = new GIFEncoder($imagedata);
		Header('Content-type:image/gif');
		echo $gif->GetAnimation();
	}
}

