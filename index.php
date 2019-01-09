<?php 




    function file_get_contents_curl_get_link($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

$list = array
(
"Company Name;/;Booth;/;URL",

);


for($i=1;$i<=3;$i++){





$html = file_get_contents_curl_get_link('https://www.affiliatesummit.com/west/affiliate-summit-west-2019-exhibitors-and-meet-market?&page='.$i.'&searchgroup=5D94235B-exhibitors');


$doc = new DOMDocument();
@$doc->loadHTML($html);
    $finder = new DomXPath($doc);
    $divs = $finder->query("/html/body/div/div[1]/main/div/div/div/div/article/div/div/div/ul/li");


     foreach ($divs as $div ) {
      
     

        $atags =  $div->getElementsByTagName( 'a' );
        $j=0;
        foreach ($atags as $atag) {
            if($j==1){
              $get_name = $atag->nodeValue;
              
            }else{
              $get_href = $atag->getAttribute('href');
              $get_href = (explode(":",$get_href))[2];
              $get_href = (explode("/",$get_href));
              $get_href = (explode("\"",$get_href[1]));
              $get_href = $get_href[0];

              $scrape_link = 'https://www.affiliatesummit.com/exhibitors/'.$get_href;
              $html = file_get_contents_curl_get_link($scrape_link);

              $doc = new DOMDocument();
              @$doc->loadHTML($html);
              $classname="mode-simple";
              $finder = new DomXPath($doc);

              $get_site_link = $finder->query("//*[contains(@class, 'entry__item__body__contacts__additional__button__website')]");


              if(isset($get_site_link[0])){
                $get_site_link = $get_site_link[0]->getElementsByTagName( 'a' );
              }else{
                $get_site_link = '';
              }

              if(isset($get_site_link[0])){
                $get_site_link = $get_site_link[0]->getAttribute('href');
              } else{
                $get_site_link = '';
              }             

              

              
             
            }
            
          $j++;
        }

        $get_divs_inside = @$div->getElementsByTagName( 'div' );
        $get_a_tag = @trim($get_divs_inside[3]->nodeValue);


        
       // exit;

       // echo $get_name.' -> '. $get_a_tag .' -> '.$get_site_link.'<br>';

        $key =$get_name.";/;".$get_a_tag.";/;".$get_site_link;
        array_push($list,$key );


     
    }






}




$file = fopen("list.csv","w");

foreach ($list as $line)
  {
  fputcsv($file,explode(';/;',$line));
  }

fclose($file);




?>


<a href="list.csv"> Download The List</a>