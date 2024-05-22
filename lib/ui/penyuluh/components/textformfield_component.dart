import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class TextFormFieldComponent extends StatelessWidget {
  final dynamic controller;
  final IconData icon;
  final String hint;
  final String label;
  final dynamic validator;
  final dynamic onSaved;
  final TextInputType inputType;
  final bool obsecure;
  final dynamic maxLine;
  final String? initialValue;

  const TextFormFieldComponent({
    super.key,
    this.controller,
    required this.icon,
    required this.hint,
    required this.label,
    required this.validator,
    required this.inputType,
    required this.obsecure,
    this.maxLine,
    this.onSaved,
    this.initialValue,
  });

  @override
  Widget build(BuildContext context) {
    return TextFormField(
      initialValue: initialValue,
      maxLines: maxLine,
      obscureText: obsecure,
      controller: controller,
      keyboardType: inputType,
      decoration: InputDecoration(
        isDense: true,
        filled: true,
        fillColor: ColorTheme().whiteColor,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10),
        ),
        prefixIcon: Icon(
          icon,
          color: ColorTheme().primaryColor,
          size: 25,
        ),
        hintText: hint,
        labelText: label,
      ),
      validator: validator,
      onSaved: onSaved,
    );
  }
}
