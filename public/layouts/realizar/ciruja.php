<div class="sol">
	<h1 style="margin-bottom: 1.5vw;">Realizar pedido de material</h1>
	<form action="#" method="post">
		<div class="left">
			<div>
				<label for="tipo_solicitud">Tipo de solicitud:</label>
				<select name="tipo_solicitud" id="tipo_solicitud" aria-readonly="true">
					<option value="Para cirugía" selected>Para cirugía</option>
				</select>
			</div>
			<div>
				<label for="fecha_solicitud">Fecha de solicitud:</label>
				<input name="fecha_solicitud" type="date" value="<?php echo date('Y-m-d'); ?>" required readonly>
			</div>
			<div>
				<label for="paciente">Nombre completo del paciente:</label>
				<input type="text" id="paciente" name="paciente" required>
			</div>
			<div>
				<label for="dni">D.N.I:</label>
				<input name="dni" type="text" oninput="formatDNI(this)" required>
			</div>
			<div>
				<label for="telefono">Telefono</label>
				<input name="telefono" type="number" required>
			</div>
			<div>
				<label for="nomencladores">Nomenclador de cirugía *En caso de no encontrarse la cirugía, escribir el nombre</label>
				<input type="text" list="nomencladores" name="nomencladores" placeholder="Escribe para buscar...">
				<datalist id="nomencladores">
					<option value="" selected disabled>Seleccionar una opción</option>
					<?php
					try {
						$stmt = $pdo->prepare("SELECT codigo, descripcion FROM nomencladorescx");
						$stmt->execute();

						$options = "";
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							$codigo = $row['codigo'];
							$descripcion = $row['descripcion'];
							$options .= "<option value='$codigo - $descripcion'>$codigo - $descripcion</option>";
						}

						// Escribir las opciones en el DOM
						echo $options;
					} catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
					}
					?>
				</datalist>
			</div>

			<div>
				<label for="categoriascie">Diagnostico CIE-10 *En caso de no encontrarse en el listado, escribir diagnostico</label>
				<input type="text" list="categoriascie" name="categoriascie" placeholder="Escribe para buscar...">
				<datalist id="categoriascie">
					<option value="" selected disabled>Seleccionar una opción</option>
					<?php
					try {
						$stmt = $pdo->prepare("SELECT clave, descripcion FROM categoriascie10");
						$stmt->execute();

						$options = "";
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							$clave = $row['clave'];
							$descripcion = $row['descripcion'];
							$options .= "<option value='$clave - $descripcion'>$clave - $descripcion</option>";
						}

						// Escribir las opciones en el DOM
						echo $options;
					} catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
					}
					?>
				</datalist>
			</div>
			<div>
				<label for="banco">Banco:</label>
				<select name="banco" id="bancoSelect" onchange="carga()" required>
					<option value="" selected disabled>Seleccionar banco</option>
					<?php
					try {
						$query = "SELECT banco, siglas FROM bancos";
						$stmt = $pdo->prepare($query);
						$stmt->execute();

						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							echo '<option value="' . $row['siglas'] . '">' . $row['banco'] . ' - ' . $row['siglas'] . '</option>';
						}
					} catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
					}
					?>
				</select>
			</div>
		</div>

		<div class="right">


			<table>
				<thead>
					<tr>
						<th>Item</th>
						<th>Descripcion</th>
						<th>Descripcion Ampiada</th>
						<th>Estudios prequirurgicos</th>
						<th>Estudios postquirurgicos</th>
						<th>Cantidad</th>
					</tr>
				</thead>
				<tbody id="tabla-resultados"></tbody>
				<tbody id="tablaCruda">
					<tr style="text-align: center; font-size: 1.4vw;">
						<td colspan="6" style="text-align: center; padding: .5vw;">Seleccione un banco para continuar</td>
					</tr>
				</tbody>
			</table>
		</div>



		<input type="submit" class="btn-verde sendButton" style="margin-top: 1.5vw;" value='Solicitar pedido firmando como "<?php

																															echo $user->getApellido() . " " . $user->getNombre() . " - Matricula: " . $user->getMatricula()

																															?>"'>
	</form>
</div>