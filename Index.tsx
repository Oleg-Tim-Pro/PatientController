import React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';
import { TableContainer, Table, TableHead, TableRow, TableCell, TableBody, Paper } from '@mui/material';

interface Patient {
  id: number;
  name: string;
  birthdate: string;
  age: string;
}

interface Props {
  patients: Patient[];
}

const Index: React.FC<Props> = ({ patients }) => {
  return (
    <div className="max-w-4xl mx-auto p-4">
    
      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>ID</TableCell>
              <TableCell>Name</TableCell>
              <TableCell>Birthdate</TableCell>
              <TableCell>Age</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {patients.map((patient) => (
              <TableRow key={patient.id}>
                <TableCell>{patient.id}</TableCell>
                <TableCell>{patient.name}</TableCell>
                <TableCell>{patient.birthdate}</TableCell>
                <TableCell>{patient.age}</TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </div>
  );
};

export default Index;
