import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { Layout } from '../components/Layout';
import { Packages } from './Packages';
import { Dashboard } from './Dashboard';
import { NotFound } from './NotFound';
import { PrivateRoute } from '../components/PrivateRoute';

const App: React.FC = () => {
    return (
        <Router>
            <Layout>
                <Routes>
                    <Route path="/" element={<Dashboard />} />
                    <Route path="/packages" element={<Packages />} />
                    <Route path="*" element={<NotFound />} />
                </Routes>
            </Layout>
        </Router>
    );
};

export default App;