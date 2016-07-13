CREATE TABLE chamado (
    id SERIAL PRIMARY KEY,
    titulo text,
    descricao text,
    providencia text,
    dataAbertura date,
    dataEncerramento date,
    morador_id int NOT NULL,
    status_id int NOT NULL);

CREATE TABLE funcionario (
    id SERIAL PRIMARY KEY,
    nome text,
    funcao text);

CREATE TABLE morador (
    id SERIAL PRIMARY KEY,
    nome text,
    apartamento int);

CREATE TABLE status (
    id SERIAL PRIMARY KEY,
    descricao text);

CREATE TABLE circular (
    id SERIAL PRIMARY KEY,
    titulo text,
    descricao text,
    cadastro date);

CREATE TABLE enquete (
    id SERIAL PRIMARY KEY,
    dataAbertura date,
    dataFechamento date,
    melhoria_id int NOT NULL,
    status_id int NOT NULL);

CREATE TABLE melhoria (
    id SERIAL PRIMARY KEY,
    titulo text,
    descricao text,
    custoAproximado float,
    custoFinal float,
    dataRealizacao date,
    prestador_id int NOT NULL);

CREATE TABLE prestador (
    id SERIAL PRIMARY KEY,
    nome text);

CREATE TABLE salao (
    id SERIAL PRIMARY KEY,
    dataReserva date,
    horaInicio text,
    horaFim text,
    morador_id int NOT NULL);

CREATE TABLE contato (
    id SERIAL PRIMARY KEY,
    tipo text,
    valor text,
    morador_id int NOT NULL,
    funcionario_id int NOT NULL,
    prestador_id int NOT NULL);

CREATE TABLE habilidade (
    id SERIAL PRIMARY KEY,
    descricao text);

CREATE TABLE prestador_habilidade (
    id SERIAL PRIMARY KEY,
    prestador_id int NOT NULL,
    habilidade_id int NOT NULL);

CREATE TABLE funcionario_habilidade (
    id SERIAL PRIMARY KEY,
    funcionario_id int NOT NULL,
    habilidade_id int NOT NULL);

CREATE TABLE enquete_morador (
    id SERIAL PRIMARY KEY,
    enquete_id int NOT NULL,
    morador_id int NOT NULL);

    ALTER TABLE salao ADD CONSTRAINT salao_morador_id_fk FOREIGN KEY (morador_id) REFERENCES morador (id);
    ALTER TABLE chamado ADD CONSTRAINT chamado_morador_id_fk FOREIGN KEY (morador_id) REFERENCES morador (id);
    ALTER TABLE chamado ADD CONSTRAINT chamado_status_id_fk FOREIGN KEY (status_id) REFERENCES status (id);
    ALTER TABLE melhoria ADD CONSTRAINT melhoria_prestador_id_fk FOREIGN KEY (prestador_id) REFERENCES prestador (id);
    ALTER TABLE enquete ADD CONSTRAINT enquete_melhoria_id_fk FOREIGN KEY (melhoria_id) REFERENCES melhoria (id);
    ALTER TABLE enquete ADD CONSTRAINT enquete_status_id_fk FOREIGN KEY (status_id) REFERENCES status (id);
    ALTER TABLE prestador_habilidade ADD CONSTRAINT prestador_habilidade_prestador_id_fk FOREIGN KEY (prestador_id) REFERENCES prestador (id);
    ALTER TABLE prestador_habilidade ADD CONSTRAINT prestador_habilidade_habilidade_id_fk FOREIGN KEY (habilidade_id) REFERENCES habilidade (id);
    ALTER TABLE funcionario_habilidade ADD CONSTRAINT funcionario_habilidade_funcionario_id_fk FOREIGN KEY (funcionario_id) REFERENCES funcionario (id);
    ALTER TABLE funcionario_habilidade ADD CONSTRAINT funcionario_habilidade_habilidade_id_fk FOREIGN KEY (habilidade_id) REFERENCES habilidade (id);
    ALTER TABLE enquete_morador ADD CONSTRAINT enquete_morador_enquete_id_fk FOREIGN KEY (enquete_id) REFERENCES enquete (id);
    ALTER TABLE enquete_morador ADD CONSTRAINT enquete_morador_morador_id_fk FOREIGN KEY (morador_id) REFERENCES morador (id);
    ALTER TABLE contato ADD CONSTRAINT contato_morador_id_fk FOREIGN KEY (morador_id) REFERENCES morador (id);
    ALTER TABLE contato ADD CONSTRAINT contato_funcionario_id_fk FOREIGN KEY (funcionario_id) REFERENCES funcionario (id);
    ALTER TABLE contato ADD CONSTRAINT contato_prestador_id_fk FOREIGN KEY (prestador_id) REFERENCES prestador (id);