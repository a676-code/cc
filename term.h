/*******************************
Title: term.h
Author: Andrew Lounsbury
Date: 7/5/2020
Purpose: terms in Polynomial.h
********************************/

#ifndef TERM_H
#define TERM_H

#include <iostream>
#include <cmath>
#include <vector>
using namespace std;

struct Term
{
	double coefficient;
	vector<int> exponents;
	Term* next;
	
	void printTerm()
	{
		cout << "\nPRINTTERM\n";
		cout << "coeff: " << coefficient << endl;
		cout << "exponents: ";
		for (int v = 0; (unsigned)v < exponents.size(); v++)
			cout << exponents.at(v) << " ";
		cout << endl;
		
		if (coefficient != 1)
			cout << "one\n";
		if (exponents.size() > 1)
			cout << "two\n";
		
		if (coefficient != 1 || exponents.size() > 1)
			cout << coefficient;
		
		for (int v = 0; (unsigned)v < exponents.size(); v++)
		{
			cout << "x";
			if (exponents.size() > 1)
				cout << "_" << v;
			if (exponents.size() > 1)
			{
				if (exponents.at(v) != 0)
					cout << "^" << exponents.at(v);
			}
		}
	}
};

#endif