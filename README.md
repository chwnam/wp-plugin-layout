# WP Plugin Layout
워드프레스 플러그인 개발을 위한 상용구 코드 (boilerplate code).

여러 플러그인 코드를 개발하면서 겪은 공통된 경험을 모아 상용구로 만들었습니다. 자주 사용되는 구조와 상황에 대해 반복을 줄이고 
문제에 대한 가장 좋은 방법들 빠르게 대입할 수 있도록 플러그인의 기초 구조를 제공합니다.



## 시작하기
### 요구사항 확인
* WordPress 5.x 버전.
* PHP 7.4 이상.

### 설치
[composer](https://getcomposer.org/) 를 통해 간단하게 설치할 수 있습니다.
아래는 워드프레스의 `wp-content/plugin`에서 'my_plugin' 디렉토리를 만들고 그 안에 상용구 코드를 설치합니다.

```
composer create-project -s alpha changwoo/wp-plugin-layout my_plugin 
```

### 접두어 일괄 변경
받아진 패키지는 그대로 새로운 플러그인의 뼈대 코드로 동작합니다. 그러나 다른 플러그인과의 충돌을 막기 위해 
워드프레스 기본 방식인 접두어(prefix)를 일괄 변경해야 합니다. 

`wp-content/plugin/my_plugin/tools` 디렉토리에 `prefix-changer.php` 스크립트가 있습니다. 이것을 CLI에서 실행합니다.

```
$ cd my_plugin
$ php tools/prefix-changer.php
```

이후 질문에 따라 적절히 기본 접두어 'WPPL'를 당신만의 접두어로 변경합니다.
접두어는 다음 규칙을 따릅니다.

* 입력시 영대소문자, 숫자와 언더바(_)를 사용할 수 있습니다.
* 문자열 맨앞과 뒤에 있는 언더바는 유효하지 않습니다. 입력시 제거됩니다.
* 접두어는 define 상수를 위해서는 모두 영대문자로 변경됩니다.
* 클래스 이름으로는 입력된 대로 영대소문자를 구분합니다.
* 파일 이름, 옵션 이름, 액션과 필터의 태그 등 문자열 내에서 사용되는 접두어는 모두 영소문자로 변경됩니다.

마지막으로 플러그인의 메인 파일 이름은 접두어 소문자로 변경됩니다.  


### 기타 텍스트 파일 변경
이후 다음 파일의 내용은 완전히 변경되지 않습니다. 꼭 확인하고 필요한 부분을 변경하세요.

* 메인 파일 헤더.
* README.md
* composer.json 
* .gitignore 


## 기타
자세한 사용법은 [github wiki](https://github.com/chwnam/wp-plugin-layout/wiki) 를 참고해 주세요.
