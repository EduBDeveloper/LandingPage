package com.coditech.gestion.service;

import com.coditech.gestion.entities.TipoEquipo;
import com.coditech.gestion.repository.TipoEquipoRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class TipoEquipoService {

    @Autowired
    private TipoEquipoRepository repo;

    public TipoEquipo guardar(TipoEquipo t) {
        return repo.save(t);
    }

    public List<TipoEquipo> listar() {
        return repo.findAll();
    }
}
