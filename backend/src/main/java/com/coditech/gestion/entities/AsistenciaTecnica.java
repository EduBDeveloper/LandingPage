package com.coditech.gestion.entities;

import com.coditech.gestion.model.Cliente;
import jakarta.persistence.*;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.PastOrPresent;
import jakarta.validation.constraints.Size;
import lombok.Data;

import java.time.LocalDate;

@Entity
@Data
public class AsistenciaTecnica {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotNull(message = "El técnico es obligatorio")
    @ManyToOne
    private Cliente tecnico;

    @NotNull(message = "La empresa es obligatoria")
    @ManyToOne
    private Empresa empresa;

    @NotNull(message = "El tipo de equipo es obligatorio")
    @ManyToOne
    private TipoEquipo tipoEquipo;

    @NotNull(message = "La fecha de asistencia es obligatoria")
    @PastOrPresent(message = "La fecha de asistencia no puede ser futura")
    private LocalDate fechaAsistencia;

    @Size(max = 255, message = "El comentario no puede superar los 255 caracteres")
    private String comentarios;
}
