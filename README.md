# Content Protector
<img src="https://img.shields.io/badge/dle-11.3-007dad.svg"> <img src="https://img.shields.io/badge/lang-tr-ff0000.svg"> <img src="https://img.shields.io/badge/lang-en-000099.svg">

İçeriklerinizin botlar tarafından çekilmesini engelleyin

## Kurulum

Dosyaları sunucuya atarak install_module.php çalıştırın ve silin.

Content Protector modülü içeriklerinizi botlardan korumaya yarar. Bunun yanında istediğiniz kullanıcı grupları için de bu korumayı kullanabilirsiniz. Koruma işlemi şimdilik sadece ReCaptcha ile sağlanıyor. Yapacağınız koruma için zaman aşımı da belirtebilirsiniz.
Belirttiğiniz zamandan sonra kullanıcıya/ziyaretçiye tekrar ReCaptcha sorulacaktır.
Sistem cookie yerine session kullanıldığı için dışarıdan yapılacak girişimlerden etkilenmez.

Çoklu kullanım desteği sayesinde, ister makale içinde ister şablon dosyasında kullanabilirsiniz.

Genel kod yapısı :

```[protect ... ] xxxx [/protect]```

Tüm parametreler :
    type : (rc|c) ReCaptcha, Captcha koruma tipleri. ( Sadece rc geçerli değer )
    expire : (5m|2h|3d) dakika, saat, gün - Zaman aşımı, bu süre sonunda kullanıcıya form tekrar gösterilecektir.
    id : Eğer yazınız içindeki bir parçayı korumaya alacaksanız id değerini benzersiz bir biçimde tanımlamalısınız.
    group : Koruma uygulanacak kullanıcı grup id leri. Örnek: 3,4,5

Full story de belirli bir kısmı korumak için aşağıdaki kodu yazını içerisinde kullanabilirsiniz

```[protect type="rc" group="5" expire="30m" id="xyz"]
	Makaledeki korumalı yazı
[/protect]```


Makalenin tamamında koruma yapmak için ilave alan sistemini kullanabilirsiniz. Böylece yazı içine kod girmek yerine ilave alan yardımıyla parametreleri girebilirsiniz.
İlave alan ile yapılacak koruma için gerekli alanlar :

    lock : (liste) : Yes,No [ Not optional ]
    expire : (yazı) [ Optional ]
    group : (yazı) [ Optional ]
    type : (yazı) [ Optional ]


Bazı alanlar opsiyoneldir. Çünkü bu alanlar için "varsayılan" değerler belirtebilirsiniz.
Örnek: Zaman aşımı süresi 30m dir ( 30 dakika )

## Installation

Upload all files than run the install_module.php file and delete.

```[protect ... ] xxxx [/protect]```

All parameters :
  type    : (rc|c) ReCaptcha, Captcha protection types.
  expire  : (5m|2h|3d) minute, hour, day - Expiration time of protection. After end, users will see protection form
  id      : For partial use. If you use content protection on your full story. You must define identication text as uniquely.
  group   : User group ids for applying protect to groups. Example: 3,4,5

* To protect partial of full story text. You can use this codes in your full story text

```[protect type="rc" group="5" expire="30m" id="xyz"]
	Protected text of article
[/protect]```

* To protect all of full story, you can use xfield system. Required fields :
   lock   : (list) : Yes,No  [ Not optional ]
   expire : (text)           [ Optional ]
   group  : (text)           [ Optional ]
   type   : (text)           [ Optional ]

  Some fields are optional. Because you can specify default values for this fields.
  Example: default value of expiration time is 30m ( 30 minutes )


Add this rules any stylesheet in your template ( style.css / engine.css / ... )
```._cp { border: 1px solid #999; border-radius: 5px; padding: 10px; background: #D1DCE1; color: #333; }
._cp input { background: #338FF6; border: 0; border-radius: 3px; padding: 4px 8px; margin: 10px 5px; color: #fff; }
._cp input:hover { background: #1040A9; cursor: pointer; }
._cp_open { background: #fcfcfc; padding: 5px; border: 1px solid #ccc; }```