<?php include 'header.php'; ?>

<h1>Cart</h1>

<?php 
	echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));
?>

<style type="text/css">
table {
	background:#fff;
	border-radius:10px;
	border-collapse:collapse;
}
table th {
	text-align:left;
	background:#eee;
	padding:4px;
}

.update input,
.update span,
.update div,
.tdbLink {
	display:none;
}
table td {
	padding:4px;
	vertical-align:middle !important;
}
table td input[type="text"] {
	width:28px;
}
</style>

<table>
<tr>
	<th>Qty</th>
	<th> </th>
	<th>Name</th>
	<th>Price</th>
	<th>Delete </th>
</tr>
<?php
	$products = $cart->get_products();
	
    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
	// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }

	$k = 0;
	while ($k<sizeOf($products)) {
	echo $attributes_values[$k]['products_options_name'];
	$k ++;
	}
	
	$i=0;
	while ($i<sizeOf($products)) {
?>
<tbody style="border-bottom:2px solid #ccc;">
<tr>
	<td>
		<?php	
			echo tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']) . tep_draw_button(IMAGE_BUTTON_UPDATE, 'refresh');
		?>
	</td>
	<td class="update">
	<?php
		if ($product['buttonUpdate'] == '') {
		echo '' ;
		} else {
		echo $product['buttonUpdate'];
		}
	?>
	</td>
	<td><?php echo $products[$i]['name']; ?> </td>
	<td>$<?php echo number_format($products[$i]['final_price'] * $products[$i]['quantity'], 2); ?> </td>
	<td>
	<?php
	echo '<a rel="external" href="' . tep_href_link(FILENAME_SHOPPING_CART, 'products_id=' . $products[$i]['id'] . '&action=remove_product') . '"><img src="mobile/images/delete.png" /></a>';
	?>
	</td>
</tr>
<tr>
	<td colspan="4">
	  <?php 
		echo $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
      ?>
	</td>
</tr>
</tbody>
<?php
	$i++;
	}
?>
<tr>
	<td colspan="3" align="right">Total</td>
	<td>$<?php echo number_format($cart->show_total(), 2); ?></td>
</tr>
<tr>
<td colspan="4" style="text-align:center;">
<input type="submit" value="Update Cart">
</td>
</tr>
</table>

<div style="text-align:center; padding-top:10px;">
	<a rel="external" href="/ext/modules/payment/paypal/express.php">
		    <img src="mobile/images/btn_checkout_278x43.png" />
    </a>
</div>

</form>

<?php include 'footer.php'; ?>