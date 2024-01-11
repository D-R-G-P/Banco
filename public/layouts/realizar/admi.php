<div class="tabla">
		<div style="display: flex; flex-direction: row;">
			<h1>Listado de items</h1>
			<button class="btn-verde" style="margin-left: 2vw; margin-bottom: 2vw;" onclick="itemAdd()"><i class="fa-solid fa-plus"></i> Agregar item</button>
		</div>
		<div class="background" id="background" style="display: none;">
			<div class="archive">
				<div class="boton">
					<button class="btn-rojo cerrar" onclick="itemAdd()"><i class="fa-solid fa-xmark"></i></button>
				</div>

				<form action="/Banco/app/solicitable/addItemForm.php" method="post">
					<label for="banco">Banco</label>
					<select name="banco" id="banco" required>
						<option value="" selected disabled>Seleccinar banco</option>
						<?php
						try {
							$stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
							$stmt->execute();

							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$id_banco = $row['id'];
								$banco = $row['banco'];
								$siglas = $row['siglas'];

								if ($bancos == $siglas) {
									echo "<option value='$siglas' selected>$banco - $siglas</option>";
								} else {
									echo "<option value='$siglas'>$banco - $siglas</option>";
								}
							}
						} catch (PDOException $e) {
							echo 'Error: ' . $e->getMessage();
						}
						?>
					</select>

					<label for="item">Item</label>
					<input type="number" name="item" id="item" required>

					<label for="descripcion">Descripcion</label>
					<textarea name="descripcion" id="descripcion" required></textarea>

					<label for="descripcionAmpliada">Descripcion ampliada</label>
					<textarea name="descripcionAmpliada" id="descripcionAmpliada" required></textarea>

					<label for="estPre">Estudios pre quirurgicos</label>
					<textarea name="estPre" id="estPre" required></textarea>

					<label for="estPos">Estudios post quirurgicos</label>
					<textarea name="estPos" id="estPos"></textarea>

					<button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Agregar item</button>

				</form>

			</div>
		</div>

		<script>
			function itemAdd() {
				if (background.style.display == "flex") {
					background.style.display = "none";
					banco.value = "";
					item.value = "";
					descripcion.value = "";
					descripcionAmpiada.value = "";
					estPre.value = "";
					estPos.value = "";
				} else {
					background.style.display = "flex";
					banco.value = "";
					item.value = "";
					descripcion.value = "";
					descripcionAmpiada.value = "";
					estPre.value = "";
					estPos.value = "";
				}
			}
		</script>
		<!-- style="text-align: center; vertical-align: middle;" -->

		<?php
		try {
			$stmt = $pdo->prepare("SELECT i.id, i.banco, i.item, i.descripcion, i.descripcionAmpliada, i.estPre, i.estPos, i.estado
            FROM itemssolicitables AS i
            WHERE i.estado <> 'del'
            ORDER BY i.item;");
			$stmt->execute();

			echo '<table>';
			echo '<thead>';
			echo '<tr>';
			echo '<th style="text-align: center;">Banco</th>';
			echo '<th style="text-align: center;">Item</th>';
			echo '<th style="text-align: center;">Descripción</th>';
			echo '<th style="text-align: center;">Descripción ampliada</th>';
			echo '<th style="text-align: center;">Estudios prequirurgicos</th>';
			echo '<th style="text-align: center;">Estudios post quirurgicos</th>';
			echo '<th style="text-align: center;">Acciones</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$id = $row['id'];
				$banco = $row['banco'];
				$item = $row['item'];
				$descripcion = $row['descripcion'];
				$descripcionAmpliada = $row['descripcionAmpliada'];
				$estPre = $row['estPre'];
				$estPos = $row['estPos'];
				$estado = $row['estado'];

				echo '<tr style="min-height: 3vw; height: 3vw;">';
				echo '<td style="text-align: center; vertical-align: middle;">' . $banco . '</td>';
				echo '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
				echo '<td style="vertical-align: middle;">' . $descripcion . '</td>';
				echo '<td style="vertical-align: middle;">' . $descripcionAmpliada . '</td>';
				echo '<td style="vertical-align: middle;">' . $estPre . '</td>';
				echo '<td style="text-align: center; vertical-align: middle;">' . $estPos . '</td>';
				if ($estado == "act") {
					echo '<td style="vertical-align: middle; width: 8vw; text-align-last: justify;">
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/solicitable/disable?id=' . $id . '" title="Deshabilitar item"><i class="fa-regular fa-circle-check"></i></i></a>
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/solicitable/delete?id=' . $id . '" title="Eliminar item"><i class="fa-solid fa-trash"></i></a>
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/solicitable/modificar?id=' . $id . '" title="Modificar este item"><i class="fa-solid fa-pencil"></i></a>
							</td>';
				} else if ($estado == "des") {
					echo '<td style="vertical-align: middle; width: 8vw; text-align-last: justify;">
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/solicitable/enable?id=' . $id . '" title="Habilitar item"><i class="fa-regular fa-circle-xmark"></i></a>
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/solicitable/delete?id=' . $id . '" title="Eliminar item (no deberá haber stock disponible)"><i class="fa-solid fa-trash"></i></a>
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/solicitable/modificar?id=' . $id . '" title="Modificar este item"><i class="fa-solid fa-pencil"></i></a>
					</td>';
				}
				echo '</tr>';
			}

			echo '</tbody>';
			echo '</table>';
		} catch (PDOException $e) {
			echo 'Error: ' . $e->getMessage();
		}
		?>
	</div>