<ul>
  <?php foreach ($serials as $serial): ?>
    <li><?php echo $serial->getId()?> - <?php echo str_replace($name, '<strong>'.$name.'</strong>', $serial->getTitle()) ?>
    </li>
  <?php endforeach; ?>
</ul>
