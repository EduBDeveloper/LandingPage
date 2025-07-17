package com.coditech.gestion.entities;

import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import lombok.Data;

@Entity
@Data
public class TipoEquipo {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotBlank(message = "El nombre del tipo de equipo es obligatorio")
    @Size(max = 100, message = "El nombre del tipo de equipo no puede tener más de 100 caracteres")
    private String nombre;
}
