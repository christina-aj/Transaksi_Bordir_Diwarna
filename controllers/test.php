public function actionUpdate($shift_id)
    {
        $model = $this->findModel($shift_id);

        if ($this->request->isPost) {
            $model->load($this->request->post());

            if ($model->waktu_kerja === 'custom') {
                $startTime = $this->request->post('Shift')['start_time'];
                $endTime = $this->request->post('Shift')['end_time'];

               
                if ($startTime && $endTime) {
                    $startTime = \DateTime::createFromFormat('H:i', $startTime);
                    $endTime = \DateTime::createFromFormat('H:i', $endTime);
                    
                    if ($startTime && $endTime) {
                        $interval = $startTime->diff($endTime);
                        $hours = $interval->h + ($interval->i / 60);
                        $model->waktu_kerja = $hours / 9; 
                    } else {
                        $model->addError('start_time', 'Invalid start time format.');
                        $model->addError('end_time', 'Invalid end time format.');
                    }
                } else {
                    $model->addError('start_time', 'Start time is required.');
                    $model->addError('end_time', 'End time is required.');
                }
            }

            if ($model->save()) {
                return $this->redirect(['view', 'shift_id' => $model->shift_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionCreate()
    {
        $model = new Shift();

        if ($this->request->isPost) {
            $model->load($this->request->post());


            if ($model->waktu_kerja === 'custom') {
                $startTime = $this->request->post('Shift')['start_time'];
                $endTime = $this->request->post('Shift')['end_time'];

          
                if ($startTime && $endTime) {
                    $startTime = \DateTime::createFromFormat('H:i', $startTime);
                    $endTime = \DateTime::createFromFormat('H:i', $endTime);
                    
                    if ($startTime && $endTime) {
                        $interval = $startTime->diff($endTime);
                        $hours = $interval->h + ($interval->i / 60);
                        $model->waktu_kerja = $hours / 9; 

                    } else {
                        $model->addError('start_time', 'Invalid start time format.');
                        $model->addError('end_time', 'Invalid end time format.');
                    }
                } else {
                    $model->addError('start_time', 'Start time is required.');
                    $model->addError('end_time', 'End time is required.');
                }
            }

            if ($model->save()) {
                return $this->redirect(['view', 'shift_id' => $model->shift_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
