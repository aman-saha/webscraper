
<div id="document">
<h3>Document: <small>"<?php echo $documentLocation; ?></small>"</h3>
<h2><small>Document Analysis Score: </small> <?php echo $resultofAnalyzingDocument; ?></h2>
<table>
	<tr>
		<th>Probability Of Document Being Positive</th>
		<th>Probability of Document Being Negative</th>
	</tr>
	<tr>
		<td><?php echo $probabilityofDocumentBeingPositive; ?></td>
		<td><?php echo $probabilityofDocumentBeingNegative; ?></td>
	</tr>
</table>
</div>
