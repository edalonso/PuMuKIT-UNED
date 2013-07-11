<?php foreach($announces as $k => $announce): ?>
<div class="announce_element">
  <table>
   <tbody>
    <tr>
     <td>
       <div id="pic">
         <?php echo link_to(image_tag($announce->getFirstUrlPic(), 'class=announce'),  $announce->getUrl() ) ?>
       </div>
     </td>
     <td>
      <div class="info">
        <div class="title">
          <?php echo ($announce->getTitle()==""?'&nbsp;':link_to( $announce->getTitle(), $announce->getUrl()))?>
        </div>
        <div class="line2">
          <?php echo str_replace(array('&lt;', '&gt;'), array('<', '>'), $announce->getLine2Rich()) ?>
        </div>
        <div class="date">
          <?php echo $announce->getPublicDate('d/m/Y') ?> 
        </div>
      </div>
     </td>
    </tr>
   </tbody>
  </table>
</div>
<?php endforeach;?>