<?php if( isset($serial) ):?>


<!--------------------->

  <table style="width:100%">
   <tbody>
   <tr> 
    <td style="background-color:transparent" height="53" valign="top" width="20%">
      <img src="<?php echo $serial->getFirstUrlPic()?>" style="border:3px solid #000000;" height="51" width="60" />
    </td>

    <td style="background-color:transparent" valign="top" width="80%">

    <!-- TITLE -->
      <div style="padding-left:5px">
        <a href="#"><?php echo $serial->getTitle()?></a> <br />
    <!-- LINE2 (PLACE) -->
        <strong><?php echo $sf_data->getRaw('serial')->getLine2Rich()?></strong><br />
    <!-- DATE -->
        <span style="color:#990000"><strong><?php echo $serial->getPublicDate('d/m/Y')?> </strong></span>
      </div>
    </td>
   </tr>
   <tr>
     <td style="background-color:transparent" valign="top" width="20%">
       <?php if($serial->getDisplay()):?>
           <div style="padding-top: 5px; float: left">
              <a target="_black" href="/serial/index/id/<?php echo $serial->getId()?>">Ver en CANALUNED</a>
           </div>
       <?php endif ?>
     </td>
   </tr>
   </tbody>
  </table>

<!--------------------->




<?php else:?>
<p>
  Selecione alguna serie.
</p>
<?php endif?>  