import React from 'react';
import { useForm } from '@inertiajs/inertia-react';
import { TextField, Button } from '@mui/material';
import {Label} from "@headlessui/react/dist/components/label/label";

interface Props {}

const Create: React.FC<Props> = () => {
    const { data, setData, post } = useForm({
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

            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <TextField
                        id="first_name"
                        label="Имя"
                        variant="outlined"
                        fullWidth
                        required
                        value={data.first_name}
                        onChange={(e) => setData('first_name', e.target.value)}
                    />
                </div>

                <div className="mb-3">
                    <TextField
                        id="last_name"
                        label="Фамилия"
                        variant="outlined"
                        fullWidth
                        required
                        value={data.last_name}
                        onChange={(e) => setData('last_name', e.target.value)}
                    />
                </div>
                <p>Дата рождения</p>
                <div className="mb-3">
                    <TextField
                        id="birthdate"
                        type="date"
                        variant="outlined"
                        fullWidth
                        required
                        value={data.birthdate}
                        onChange={(e) => setData('birthdate', e.target.value)}
                    />
                </div>

                <Button type="submit" variant="contained" color="primary">
                    Сохранить
                </Button>
            </form>
        </div>
    );
};

export default Create;
