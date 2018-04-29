<?php

require __DIR__."/../vendor/autoload.php";

use Dancesmile\Dttp;

$client =  Dttp::client([
  "base_uri" => "https://passport.baidu.com"
]);


$params  = urldecode("staticpage=https%3A%2F%2Fwww.baidu.com%2Fcache%2Fuser%2Fhtml%2Fv3Jump.html&charset=UTF-8&token=a3ae5b38e6fb5bebfe5b091b1526c63a&tpl=mn&subpro=&apiver=v3&tt=1524986113286&codestring=tcG6706c1d6297ce20002bb15c94301c77e35f898071f047bb5&safeflg=0&u=https%3A%2F%2Fwww.baidu.com%2F&isPhone=false&detect=1&gid=A660A53-B72F-44CB-B3DB-194D18BB4CA2&quick_user=0&logintype=dialogLogin&logLoginType=pc_loginDialog&idc=&loginmerge=true&splogin=rate&username=girlswithme%40163.com&password=NEhikZLlxslkW7iPlFm%2BtjZcYeFtKlLswT6kTpPQy0rlNOKI1kJgeOoByNKXemLV7qwhsQ7t8D%2FrWQ%2F7JkkPVuI%2FeK1PvXLb8yAUzInQNrQb94MvhfNjY4v%2FpITVOSS654QmCBe1HM2DdnfhD111WNiqoRliWWARaxz1pcxYpo0%3D&verifycode=%E6%8A%95%E5%BD%B1&mem_pass=on&rsakey=WGDMf3JUuV1YtP1XZCExX76kACdnZthy&crypttype=12&ppui_logintime=21903&countrycode=&fp_uid=c9bc4df836ed3f1f850a17058a587c8e&fp_info=c9bc4df836ed3f1f850a17058a587c8e002%7E%7E%7EZwZZqOpIXIazmIiQJIf_hZZGspI8Xp-8uT-80Z_ppI8Xp-8uTy61L_VZqTvBZqTvrZZYYZZ6Dp0CZLcfk6uifnz7d60Ef8XhQnXrxLcB1nh81nXZy61Zi8XqiAh-16XGQnXZ180hiA%7ELQ8XEB61Go61T-AU6enlJPOu6%7EthZZGhZZRhZZPhZv%7Ep0VqEcWd3ca2OctqOo6wOItK3qJjLlwP3YBaOla2Oct1to%7EhJHJ9OlteNI6%7EOU6%7ELoJjLUveGH%7E2GI6VOoJptqw8GHthNISj3l%7EhJIWzOoiQJIiQ52PoJHs1NIWKnzqK6Y-i5zqf61G9Fp0vuJctlGHte3u-B6X%7EPGlpf8032J0JlJ0E16I81Go7oJzZf8o6z80716XZyGz3lJzPlGzJl60LB60pi8Xqw8Is2GoGw6zJP6IJ%7EAzTwJz7yGoalGXa%7E6XGwJIG1ncqfnIs260LwnXEQJzP%7EJI71G1h1n0Lf6lJ%7E80r2GzPhncpQ6lE1Jc7dnIaznctxJctlGHte3uizJl7i816%7E8z626cG18oGfJXLo8I6%7E80qo8I7QJzJP8cJ26lpwn0p1Glqf80ZiJzqdnIJPnXqQ6XLyJcE1n062NhZvShZv-hZvIhZvXZZpxpzRlTF7Q-_zpI3IiVOlWyOT__ZhZvHZZZChZZEhZZLhZZWhZZehZZkpV5XqK60pB607-n0p1n0LQ61pQ67__&loginversion=v4&dv=tk0.065946987773609861524986091914%40ooX0ACCkSftmcG9G%7EvGSsCoeN9GehTtBhToFhOHFCFKF5Suk9etBOjCB2ftmcG9G%7EvGSsCoeN9GehTtBhToFhOHFCFKF5Suk9zCBsjCBGftmcG9G%7EvGSsCoeN9GehTtBhToFhOHFCFKF5Suk9yt15jCB9ftmcG9G%7EvGSsCoeN9GehTtBhToFhOHFCFKF5SukG-t1hjCB8ftmcG9G%7EvGSsCoeN9GehTtBhToFhOHFCFKF5SukGgtB3jqX0JoCBGeuk9%7EDA-ctBtluv-eDBHftl9%7EukHctkGfn2sD4e5hBnNTGsC9ol2-oeNaKF5QTk8FCm-lDB8fDB8lCgcG9G%7EvGSsCoeN9GehTtBhToFI3Ha3aLGCbIrn5K6PjCmmJImBdko%7EJhBXitg-guk8zuXgPrf-u1qzCBSwC1SyClHFtl8-DBUztBGgCkSyC1q%7EtBScCq__iXpJv4wHvtiugNFPFHE8asVIvGE8zNQugceKa43Ia3EI69_FXgtm--uk2gDkqfCl0zuk2gDkqfDkq-uk2gDkqftB0ytm-ytkq_&traceid=8E962B01&callback=parent.bd__pcbs__lhumjl");


parse_str($params,$fromData);

$index_html = $client->redirect(true)->verify(false)
 ->asFormParams()->withOptions([
 	"Upgrade-Insecure-Requests" => 1
 ])->post("/v2/api/?login",$fromData)->body();

dd($index_html);
