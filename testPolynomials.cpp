/*******************************
Title: polynomialTEST.cpp
Author: Andrew Lounsbury
Date: 6/3/2020
Purpose: test polynomial class
********************************/

#include "Polynomial.h"
#include <iostream>
#include <math.h>
using namespace std;

Polynomial operator+(Polynomial&, Polynomial&);

int main()
{
	int nT1 = -1, nV1 = -1, nT2 = -1, nV2 = -1;
	
	cout << "*******************************************\n";
	// cout << "Enter the number of terms:  ";
	// cin >> nT1;
	nT1 = 2;
	// cout << "Enter the number of variables:  ";
	// cin >> nV1;
	nV1 = 1;
	
	Polynomial* p = new Polynomial(nT1, nV1, 0);
	p->enterInfo();
	p->lexOrder();

	cout << "*******************************************\n";
	// cout << "Enter the number of terms:  ";
	// cin >> nT2;
	nT2 = 2;
	// cout << "Enter the number of variables:  ";
	// cin >> nV2;
	nV2 = 1;
	
	Polynomial* q = new Polynomial(nT2, nV2, 0);
	q->enterInfo();
	q->lexOrder();
	
	cout << "\np = ";
	p->printPolynomial();
	cout << "q = ";
	q->printPolynomial();
	
	p->getTerm(1)->printTerm();
	p->getTerm(2)->printTerm();
	q->getTerm(1)->printTerm();
	q->getTerm(2)->printTerm();
	
	// WORKS
	// cout << "\nEQUALITY:\n";
	// cout << "----------\n";
	// if (*p == *q)
		// cout << "p = q\n";
	// else
		// cout << "p != q\n";
	
	// cout << "\nINEQUALITY:\n";
	// cout << "------------\n";
	// if (*p < *q)
		// cout << "p < q\n";
	// else if (*p == *q)
		// cout << "p = q\n";
	// else
		// cout << "p > q\n";
	
	cout << "\nADDITION:\n";
	cout << "----------\n";
	cout << "p + q = ";
	(*p + *q).printPolynomial();
	
	// cout << "\nSUBTRACTION:\n";
	// cout << "----------\n";
	// cout << "p - q = ";
	// (*p + *q).printPolynomial();
	
	cout << "\nMULTIPLICATION:\n";
	cout << "p * q = ";
	(*p * *q).printPolynomial();
	
	// derivative
	cout << "DERIVATION:\n";
	cout << "----------\n";
	cout << "\n(d/dx)p = p' = ";
	p->getDerivative(0, 0)->printPolynomial();
	
	cout << "\n(d/dx_1)p = ";
	p->getDerivative(1, 0)->printPolynomial();
	
	// FIX
	cout << "\np'(2) = " << p->getDerivative(0, 0)->eval(2);
	
	return 0;
}

Polynomial operator+(Polynomial &l, Polynomial &r)
{		
	Polynomial poly;
	
	// constructor creates a single NULL node; if so, do nothing
	// if (l == NULL)
		// return r;
	// else if (&r == NULL || (l == NULL && &r == NULL))
		// return l;
	
	cout << "\nNT: " << l.getNumTerms() << endl;
	cout << "NT: " << r.getNumTerms() << endl;
	
	for (int t = 0; t < l.getNumTerms(); t++)
		poly.addTerm(l.getTerm(t));
	for (int t = 0; t < r.getNumTerms(); t++)
		poly.addTerm(r.getTerm(t));
	
	poly.simplify();
	return poly;
}
