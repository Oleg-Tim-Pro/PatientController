import React from 'react';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-react';
import { TextField, Button } from '@mui/material';

interface Props {}

const Create: React.FC<Props> = () => {
  const { data, setData, errors, post } = useForm({
    first_name: '',
    last_name: '',
    birthdate: '',
  });

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    post('/patients');
  };

  return (
    <div className="max-w-md mx-auto p-4">
      <h1 className="text-2xl font-semibold mb-4">Create Patient</h1>

      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <TextField
            id="first_name"
            label="First Name"
            variant="outlined"
            fullWidth
            value={data.first_name}
            onChange={(e) => setData('first_name', e.target.value)}
            error={!!errors.first_name}
            helperText={errors.first_name}
          />
        </div>

        <div className="mb-3">
          <TextField
            id="last_name"
            label="Last Name"
            variant="outlined"
            fullWidth
            value={data.last_name}
            onChange={(e) => setData('last_name', e.target.value)}
            error={!!errors.last_name}
            helperText={errors.last_name}
          />
        </div>

        <div className="mb-3">
          <TextField
            id="birthdate"
            label="Birthdate"
            type="date"
            variant="outlined"
            fullWidth
            value={data.birthdate}
            onChange={(e) => setData('birthdate', e.target.value)}
            error={!!errors.birthdate}
            helperText={errors.birthdate}
          />
        </div>

        <Button type="submit" variant="contained" color="primary">
          Save
        </Button>
      </form>
    </div>
  );
};

export default Create;
