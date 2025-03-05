import React from 'react';
import { Container } from 'react-bootstrap';
import { Navbar } from '../Navbar';
import { Footer } from '../Footer';

interface LayoutProps {
    children: React.ReactNode;
}

export const Layout: React.FC<LayoutProps> = ({ children }) => {
    return (
        <div className="app-layout">
            <Navbar />
            <main className="main-content">
                <Container fluid>{children}</Container>
            </main>
            <Footer />
        </div>
    );
};
